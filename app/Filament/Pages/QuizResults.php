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
    public ?int $attempt = null;

    public ?QuizAttempt $attemptModel = null;

    public ?Quiz $quiz = null;

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
            'answers.answerChoice',
            'answers.question',
        ])->findOrFail($this->attempt);

        // Check if this attempt belongs to the current user
        if ($this->attemptModel->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to quiz results.');
        }

        $this->quiz = $this->attemptModel->quiz;

        // Load attempt history for this quiz
        $this->attemptHistory = QuizAttempt::where('quiz_id', $this->quiz->id)
            ->where('user_id', Auth::id())
            ->where('status', 'completed')
            ->orderBy('completed_at', 'desc')
            ->get()
            ->toArray();
    }

    #[Computed]
    public function correctAnswers(): int
    {
        $correct = 0;

        foreach ($this->quiz->questions as $question) {
            $userAnswer = $this->attemptModel->answers
                ->where('question_id', $question->id)
                ->first();

            $correctChoice = $question->answerChoices
                ->where('is_correct', true)
                ->first();

            if ($userAnswer && $correctChoice && $userAnswer->answer_choice_id == $correctChoice->id) {
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
            $userAnswer = $this->attemptModel->answers
                ->where('question_id', $question->id)
                ->first();

            $correctChoice = $question->answerChoices
                ->where('is_correct', true)
                ->first();

            if ($userAnswer && $correctChoice && $userAnswer->answer_choice_id != $correctChoice->id) {
                $incorrect++;
            }
        }

        return $incorrect;
    }

    #[Computed]
    public function unansweredQuestions(): int
    {
        $answered = $this->attemptModel->answers->pluck('question_id')->toArray();
        $totalQuestions = $this->quiz->questions->count();

        return $totalQuestions - count($answered);
    }

    #[Computed]
    public function timeSpent(): string
    {
        if (! $this->attemptModel->started_at || ! $this->attemptModel->completed_at) {
            return 'N/A';
        }

        $start = Carbon::parse($this->attemptModel->started_at);
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
        $maxPoints = $this->quiz->max_points ?? 100;
        $score = $this->attemptModel->score ?? 0;

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

    public function isCorrectAnswer(int $questionId): bool
    {
        $question = $this->quiz->questions->where('id', $questionId)->first();
        if (! $question) {
            return false;
        }

        $userAnswer = $this->attemptModel->answers
            ->where('question_id', $questionId)
            ->first();

        $correctChoice = $question->answerChoices
            ->where('is_correct', true)
            ->first();

        return $userAnswer && $correctChoice && $userAnswer->answer_choice_id == $correctChoice->id;
    }

    public function isAnswered(int $questionId): bool
    {
        return $this->attemptModel->answers
            ->where('question_id', $questionId)
            ->isNotEmpty();
    }

    public function getUserAnswer(int $questionId): ?int
    {
        $answer = $this->attemptModel->answers
            ->where('question_id', $questionId)
            ->first();

        return $answer ? $answer->answer_choice_id : null;
    }

    public function getActions(): array
    {
        return [
            Action::make('back')
                ->label('Quay lại danh sách Quiz')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(route('filament.app.pages.my-quiz')),

            Action::make('retake')
                ->label('Làm lại')
                ->icon('heroicon-o-arrow-path')
                ->color('primary')
                ->visible(fn () => $this->canRetakeQuiz())
                ->url(fn () => route('filament.app.pages.quiz-taking', ['quiz' => $this->quiz->id])),
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
