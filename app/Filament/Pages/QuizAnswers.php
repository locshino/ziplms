<?php

// app/Filament/Pages/QuizAnswers.php

namespace App\Filament\Pages;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

// use BezhanSalleh\FilamentShield\Traits\HasPageShield;

class QuizAnswers extends Page
{
    // use HasPageShield;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-academic-cap';

    protected string $view = 'filament.pages.quiz-answers';

    protected static bool $shouldRegisterNavigation = false;

    // --- Properties for Quiz Data ---
    public ?Quiz $quiz = null;

    public ?QuizAttempt $attempt = null;

    public ?QuizAttempt $attemptModel = null;

    public Collection $allAttempts;

    public ?string $selectedAttemptId = null;

    // --- Properties for Statistics ---
    public int $score = 0;

    public int $maxScore = 0;

    public float $percentage = 0;

    public ?string $timeSpent = null;

    public ?string $completedAt = null;

    public ?string $duration = null;

    public int $correctCount = 0;

    public int $wrongCount = 0;

    public int $unansweredCount = 0;

    // --- Properties for UI State ---
    public string $filter = 'all'; // 'all', 'correct', 'wrong', 'unanswered'

    public Collection $results;

    /**
     * Runs when the component is initialized.
     * Fetches data, calculates stats, and prepares results for the view.
     */
    public function mount(): void
    {
        $quizId = request()->get('quiz');

        // Ensure a quiz ID is provided
        if (! $quizId) {
            $this->sendErrorNotification('Không tìm thấy thông tin quiz.', 'filament.app.pages.my-quiz');

            return;
        }

        // Eager load relationships for efficiency
        $this->quiz = Quiz::with('questions.answerChoices')->find($quizId);

        // Get all completed attempts for this quiz
        $this->allAttempts = QuizAttempt::with('quizAnswers.answerChoice')
            ->where('quiz_id', $quizId)
            ->where('student_id', Auth::id())
            ->whereIn('status', ['completed', 'submitted'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Ensure quiz exists and has attempts
        if (! $this->quiz || $this->allAttempts->isEmpty()) {
            $this->sendErrorNotification('Không tìm thấy kết quả quiz hoặc bạn chưa hoàn thành quiz này.', 'filament.app.pages.my-quiz');

            return;
        }

        // Get selected attempt ID from request or use the latest one
        $this->selectedAttemptId = request()->get('attempt_id') ?? $this->allAttempts->first()->id;
        $this->attempt = $this->allAttempts->where('id', $this->selectedAttemptId)->first();

        if (! $this->attempt) {
            $this->attempt = $this->allAttempts->first();
            $this->selectedAttemptId = $this->attempt->id;
        }

        // Sync attemptModel with attempt for compatibility
        $this->attemptModel = $this->attempt;

        $this->calculateAndPrepareResults();
    }

    /**
     * Switch to a different attempt
     */
    public function selectAttempt($attemptId)
    {
        $this->selectedAttemptId = $attemptId;
        $this->attempt = $this->allAttempts->where('id', $attemptId)->first();

        if ($this->attempt) {
            $this->calculateAndPrepareResults();
        }
    }

    /**
     * A single method to process all statistics and prepare a structured
     * collection for easy rendering in the Blade view.
     */
    protected function calculateAndPrepareResults(): void
    {
        // Basic stats from the attempt
        $this->score = $this->attempt->points ?? 0; // Use points instead of score
        $this->maxScore = $this->quiz->questions->sum('pivot.points') ?? 0; // Get max points from pivot table
        $this->percentage = $this->maxScore > 0 ? round(($this->score / $this->maxScore) * 100, 2) : 0;
        $this->completedAt = $this->attempt->completed_at?->format('d/m/Y H:i');
        $this->timeSpent = $this->attempt->time_spent ? gmdate('H:i:s', $this->attempt->time_spent) : null;

        // Calculate quiz completion duration
        if ($this->attempt->start_at && $this->attempt->completed_at) {
            $startTime = $this->attempt->start_at;
            $endTime = $this->attempt->completed_at;
            $durationInSeconds = $startTime->diffInSeconds($endTime);

            // Convert seconds to hours:minutes:seconds format
            $hours = floor($durationInSeconds / 3600);
            $minutes = floor(($durationInSeconds % 3600) / 60);
            $seconds = $durationInSeconds % 60;
            $this->duration = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
        }

        $userAnswers = $this->attempt->quizAnswers->keyBy('question_id');
        $preparedResults = new Collection;

        // Loop through all quiz questions to determine the status of each
        foreach ($this->quiz->questions as $question) {
            $questionUserAnswers = $userAnswers->where('question_id', $question->id);
            $correctChoiceIds = $question->answerChoices->where('is_correct', true)->pluck('id')->toArray();
            $userChoiceIds = $questionUserAnswers->pluck('answer_choice_id')->toArray();
            $status = 'unanswered';
            $isCorrect = false;

            if ($questionUserAnswers->isNotEmpty()) {
                // Check if user answered correctly (all correct choices selected, no incorrect ones)
                if (
                    count($correctChoiceIds) === count($userChoiceIds) &&
                    empty(array_diff($correctChoiceIds, $userChoiceIds))
                ) {
                    $status = 'correct';
                    $isCorrect = true;
                    $this->correctCount++;
                } else {
                    $status = 'wrong';
                    $this->wrongCount++;
                }
            } else {
                $this->unansweredCount++;
            }

            // Add all relevant info for this question to a structured collection
            $preparedResults->push([
                'question' => $question,
                'user_answers' => $questionUserAnswers, // Changed to plural for multiple answers
                'correct_choices' => $question->answerChoices->where('is_correct', true), // All correct choices
                'status' => $status,
                'is_correct' => $isCorrect,
            ]);
        }

        $this->results = $preparedResults;
    }

    /**
     * Sets the active filter for the detailed answers view.
     * This is a Livewire action triggered by button clicks.
     */
    public function setFilter(string $filter): void
    {
        $this->filter = $filter;
    }

    /**
     * Helper to send a notification and redirect.
     */
    protected function sendErrorNotification(string $message, string $route): void
    {
        Notification::make()
            ->title('Lỗi')
            ->body($message)
            ->danger()
            ->send();

        // Use `redirect()` helper which is safer in Livewire
        redirect()->route($route);
    }

    // --- Filament Page Configuration ---

    public function getTitle(): string
    {
        return 'Đáp án: '.($this->quiz?->title ?? 'Quiz');
    }

    public function isCorrectAnswer(string $questionId): bool
    {
        $question = $this->quiz->questions->where('id', $questionId)->first();
        if (! $question) {
            return false;
        }

        $userAnswer = $this->attemptModel->studentAnswers
            ->where('question_id', $questionId)
            ->first();

        $correctChoice = $question->answerChoices
            ->where('is_correct', true)
            ->first();

        return $userAnswer && $correctChoice && $userAnswer->answer_choice_id == $correctChoice->id;
    }

    public function isAnswered(string $questionId): bool
    {
        return $this->attemptModel->studentAnswers
            ->where('question_id', $questionId)
            ->isNotEmpty();
    }

    public function getHeading(): string
    {
        return $this->quiz?->title ?? 'Quiz';
    }

    public function getSubheading(): string
    {
        return '';
    }
}
