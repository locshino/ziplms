<?php

namespace App\Filament\Pages;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Services\Interfaces\QuizServiceInterface;
use BackedEnum;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;

class QuizResults extends Page
{
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-chart-bar';

    protected string $view = 'filament.pages.quiz-results';

    protected static ?string $title = 'Kết quả Quiz';

    protected static bool $shouldRegisterNavigation = false;

    #[Url]
    public ?string $attempt = null;

    public ?QuizAttempt $attemptModel = null;

    public ?Quiz $quiz = null;

    public float $percentage = 0;

    public int $correctAnswers = 0;

    public int $incorrectAnswers = 0;

    public int $unansweredQuestions = 0;

    public array $attemptHistory = [];

    protected QuizServiceInterface $quizService;

    public function boot(QuizServiceInterface $quizService): void
    {
        $this->quizService = $quizService;
    }

    public function mount(): void
    {
        if (! $this->attempt) {
            $this->redirect(route('filament.app.pages.my-quiz'));

            return;
        }

        $this->attemptModel = QuizAttempt::with([
            'quiz.course',
            'quiz.questions.answerChoices',
            'studentAnswers.answerChoice',
            'studentAnswers.question',
        ])->findOrFail($this->attempt);

        // Check if this attempt belongs to the current user
        if ($this->attemptModel->student_id !== Auth::id()) {
            abort(403, 'Unauthorized access to quiz results.');
        }

        $this->quiz = $this->attemptModel->quiz;

        // Calculate percentage and correct answers
        // Get max score from pivot table (quiz_questions.points)
        $maxScore = $this->quiz->questions->sum('pivot.points');
        $this->percentage = $maxScore > 0 ? ($this->attemptModel->points / $maxScore) * 100 : 0;
        // Ensure percentage doesn't exceed 100%
        $this->percentage = min($this->percentage, 100);

        // Count correct, incorrect and unanswered questions
        $this->correctAnswers = 0;
        $this->incorrectAnswers = 0;
        $this->unansweredQuestions = 0;
        foreach ($this->quiz->questions as $question) {
            if ($this->isAnswered($question->id)) {
                if ($this->isCorrectAnswer($question->id)) {
                    $this->correctAnswers++;
                } else {
                    $this->incorrectAnswers++;
                }
            } else {
                $this->unansweredQuestions++;
            }
        }

        // Load attempt history for this quiz
        $attempts = QuizAttempt::where('quiz_id', $this->quiz->id)
            ->where('student_id', Auth::id())
            ->where('status', 'completed')
            ->orderBy('end_at', 'desc')
            ->get();

        // Calculate percentage for each attempt
        $maxScore = $this->quiz->questions->sum('pivot.points');
        $this->attemptHistory = $attempts->map(function ($attempt) use ($maxScore) {
            $attemptArray = $attempt->toArray();
            $percentage = $maxScore > 0 ? ($attempt->points / $maxScore) * 100 : 0;
            // Ensure percentage doesn't exceed 100%
            $attemptArray['percentage'] = round(min($percentage, 100), 2);
            $attemptArray['completed_at'] = $attempt->end_at; // Add completed_at field
            return $attemptArray;
        })->toArray();
    }

    #[Computed]
    public function correctAnswers(): int
    {
        $correct = 0;

        foreach ($this->quiz->questions as $question) {
            $userAnswers = $this->attemptModel->studentAnswers
                ->where('question_id', $question->id);

            $correctChoiceIds = $question->answerChoices
                ->where('is_correct', true)
                ->pluck('id')
                ->toArray();

            $userChoiceIds = $userAnswers->pluck('answer_choice_id')->toArray();

            // Check if user answered correctly (all correct choices selected, no incorrect ones)
            if (
                count($correctChoiceIds) === count($userChoiceIds) &&
                empty(array_diff($correctChoiceIds, $userChoiceIds))
            ) {
                $correct++;
            }
        }

        return $correct;
    }

    #[Computed]
    public function incorrectAnswers(): int
    {
        $incorrect = 0;

        foreach ($this->quiz->questions as $question) {
            $userAnswers = $this->attemptModel->studentAnswers
                ->where('question_id', $question->id);

            $correctChoiceIds = $question->answerChoices
                ->where('is_correct', true)
                ->pluck('id')
                ->toArray();

            $userChoiceIds = $userAnswers->pluck('answer_choice_id')->toArray();

            // Check if user answered incorrectly (not all correct choices or has incorrect ones)
            if (
                $userAnswers->isNotEmpty() && (
                    count($correctChoiceIds) !== count($userChoiceIds) ||
                    !empty(array_diff($correctChoiceIds, $userChoiceIds))
                )
            ) {
                $incorrect++;
            }
        }

        return $incorrect;
    }

    #[Computed]
    public function unansweredQuestions(): int
    {
        $answered = $this->attemptModel->studentAnswers->pluck('question_id')->toArray();
        $totalQuestions = $this->quiz->questions->count();

        return $totalQuestions - count($answered);
    }

    #[Computed]
    public function timeSpent(): string
    {
        if (! $this->attemptModel->start_at || ! $this->attemptModel->completed_at) {
            return 'N/A';
        }

        $start = Carbon::parse($this->attemptModel->start_at);
        $end = Carbon::parse($this->attemptModel->completed_at);

        $diff = $start->diff($end);

        if ($diff->h > 0) {
            return sprintf('%d giờ %d phút', $diff->h, $diff->i);
        } elseif ($diff->i > 0) {
            return sprintf('%d phút %d giây', $diff->i, $diff->s);
        } else {
            return sprintf('%d giây', $diff->s);
        }
    }

    #[Computed]
    public function percentage(): float
    {
        $maxPoints = $this->quiz->questions->sum('pivot.points') ?? 100;
        $score = $this->attemptModel->points ?? 0;

        return $maxPoints > 0 ? round(($score / $maxPoints) * 100, 2) : 0;
    }

    #[Computed]
    public function performanceData(): array
    {
        return [
            'correct' => $this->correctAnswers,
            'incorrect' => $this->incorrectAnswers,
            'unanswered' => $this->unansweredQuestions,
        ];
    }

    public function isCorrectAnswer(string $questionId): bool
    {
        $question = $this->quiz->questions->where('id', $questionId)->first();
        if (! $question) {
            return false;
        }

        $userAnswers = $this->attemptModel->studentAnswers
            ->where('question_id', $questionId);

        $correctChoiceIds = $question->answerChoices
            ->where('is_correct', true)
            ->pluck('id')
            ->toArray();

        $userChoiceIds = $userAnswers->pluck('answer_choice_id')->toArray();

        // Check if user answered correctly (all correct choices selected, no incorrect ones)
        return $userAnswers->isNotEmpty() &&
            count($correctChoiceIds) === count($userChoiceIds) &&
            empty(array_diff($correctChoiceIds, $userChoiceIds));
    }

    public function isAnswered(string $questionId): bool
    {
        return $this->attemptModel->studentAnswers
            ->where('question_id', $questionId)
            ->isNotEmpty();
    }

    public function getUserAnswer(string $questionId): ?string
    {
        $answer = $this->attemptModel->studentAnswers
            ->where('question_id', $questionId)
            ->first();

        return $answer ? $answer->answer_choice_id : null;
    }

    public function getUserAnswers(string $questionId): array
    {
        return $this->attemptModel->studentAnswers
            ->where('question_id', $questionId)
            ->pluck('answer_choice_id')
            ->toArray();
    }

    public function getActions(): array
    {
        return [
            Action::make('back')
                ->label('Quay lại danh sách Quiz')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(route('filament.app.pages.my-quiz')),


        ];
    }

    protected function canRetakeQuiz(): bool
    {
        try {
            return $this->quizService->canTakeQuiz($this->quiz->id, Auth::id());
        } catch (\Exception $e) {
            return false;
        }
    }
}
