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
use Livewire\WithPagination;

// use BezhanSalleh\FilamentShield\Traits\HasPageShield;

class QuizResults extends Page
{
    use WithPagination;
    // use HasPageShield;

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

    public int $partiallyCorrectAnswers = 0;

    public int $unansweredQuestions = 0;

    public array $attemptHistory = [];

    // Pagination properties
    public int $perPage = 10;

    public int $currentPage = 1;

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

        // Count correct, incorrect, partially correct and unanswered questions
        $this->correctAnswers = 0;
        $this->incorrectAnswers = 0;
        $this->partiallyCorrectAnswers = 0;
        $this->unansweredQuestions = 0;
        foreach ($this->quiz->questions as $question) {
            if ($this->isAnswered($question->id)) {
                $answerStatus = $this->getAnswerStatus($question->id);
                if ($answerStatus === 'correct') {
                    $this->correctAnswers++;
                } elseif ($answerStatus === 'partially_correct') {
                    $this->partiallyCorrectAnswers++;
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
                    ! empty(array_diff($correctChoiceIds, $userChoiceIds))
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

    public function getAnswerStatus(string $questionId): string
    {
        $question = $this->quiz->questions->where('id', $questionId)->first();
        if (! $question) {
            return 'unanswered';
        }

        $userAnswers = $this->attemptModel->studentAnswers
            ->where('question_id', $questionId);

        if ($userAnswers->isEmpty()) {
            return 'unanswered';
        }

        $correctChoiceIds = $question->answerChoices
            ->where('is_correct', true)
            ->pluck('id')
            ->toArray();

        $userChoiceIds = $userAnswers->pluck('answer_choice_id')->toArray();
        $userCorrectChoices = array_intersect($userChoiceIds, $correctChoiceIds);
        $userIncorrectChoices = array_diff($userChoiceIds, $correctChoiceIds);

        // If user selected incorrect choices, it's incorrect
        if (! empty($userIncorrectChoices)) {
            return 'incorrect';
        }

        // If user selected all correct choices, it's correct
        if (count($userCorrectChoices) === count($correctChoiceIds)) {
            return 'correct';
        }

        // If user selected some correct choices but not all, it's partially correct
        if (! empty($userCorrectChoices)) {
            return 'partially_correct';
        }

        return 'incorrect';
    }

    public function isCorrectAnswer(string $questionId): bool
    {
        return $this->getAnswerStatus($questionId) === 'correct';
    }

    public function isPartiallyCorrectAnswer(string $questionId): bool
    {
        return $this->getAnswerStatus($questionId) === 'partially_correct';
    }

    public function getCorrectAnswersCount(string $questionId): array
    {
        $question = $this->quiz->questions->where('id', $questionId)->first();
        if (! $question) {
            return ['selected' => 0, 'total' => 0];
        }

        $correctChoiceIds = $question->answerChoices
            ->where('is_correct', true)
            ->pluck('id')
            ->toArray();

        $userAnswers = $this->attemptModel->studentAnswers
            ->where('question_id', $questionId);

        $userChoiceIds = $userAnswers->pluck('answer_choice_id')->toArray();
        $userCorrectChoices = array_intersect($userChoiceIds, $correctChoiceIds);

        return [
            'selected' => count($userCorrectChoices),
            'total' => count($correctChoiceIds),
        ];
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

    // Pagination methods
    #[Computed]
    public function paginatedQuestions()
    {
        $questions = $this->quiz->questions;
        $total = $questions->count();
        $offset = ($this->currentPage - 1) * $this->perPage;

        return $questions->slice($offset, $this->perPage);
    }

    #[Computed]
    public function getTotalPages(): int
    {
        return (int) ceil($this->quiz->questions->count() / $this->perPage);
    }

    #[Computed]
    public function getPaginationInfo(): array
    {
        $totalQuestions = $this->quiz->questions->count();
        $totalPages = $this->getTotalPages();
        $startItem = ($this->currentPage - 1) * $this->perPage + 1;
        $endItem = min($this->currentPage * $this->perPage, $totalQuestions);

        return [
            'current_page' => $this->currentPage,
            'total_pages' => $totalPages,
            'total_items' => $totalQuestions,
            'start_item' => $startItem,
            'end_item' => $endItem,
            'per_page' => $this->perPage,
            'has_next' => $this->hasNextPage(),
            'has_previous' => $this->hasPreviousPage(),
        ];
    }

    public function hasNextPage(): bool
    {
        return $this->currentPage < $this->getTotalPages();
    }

    public function hasPreviousPage(): bool
    {
        return $this->currentPage > 1;
    }

    public function nextPage(): void
    {
        if ($this->hasNextPage()) {
            $this->currentPage++;
        }
    }

    public function previousPage(): void
    {
        if ($this->hasPreviousPage()) {
            $this->currentPage--;
        }
    }

    public function goToPage(int $page): void
    {
        if ($page >= 1 && $page <= $this->getTotalPages()) {
            $this->currentPage = $page;
        }
    }
}
