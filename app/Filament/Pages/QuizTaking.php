<?php

namespace App\Filament\Pages;

use App\Enums\Status\QuizStatus;
use App\Enums\Status\QuizAttemptStatus;
use App\Libs\Roles\RoleHelper;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Services\Interfaces\QuizServiceInterface;
use BackedEnum;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;

class QuizTaking extends Page
{
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-academic-cap';

    // [SỬA LỖI 1] - Trỏ đến đúng file view, không phải tên route
    protected string $view = 'filament.pages.quiz-taking';

    protected static ?string $title = 'Làm bài Quiz';

    protected static bool $shouldRegisterNavigation = false;

    #[Url]
    public string|int|null $quiz = null;

    public ?Quiz $quizModel = null;

    public ?QuizAttempt $attempt = null;

    public array $answers = [];

    public int $answeredCount = 0;

    public ?int $remainingSeconds = null;

    public bool $isUnlimited = true;

    public bool $timeWarning = false;

    public bool $submitting = false;

    protected QuizServiceInterface $quizService;

    public function boot(QuizServiceInterface $quizService): void
    {
        $this->quizService = $quizService;
    }

    public function mount(): void
    {
        if (! $this->quiz) {
            $this->redirect(route('filament.app.pages.my-quiz'));

            return;
        }

        // Sử dụng findOrFail để tìm bằng ID (có thể là int hoặc UUID string)
        $this->quizModel = Quiz::with(['courses', 'questions.answerChoices'])
            ->findOrFail($this->quiz);
        // Check if user can take this quiz with simplified logic
        $canTake = $this->canTakeQuiz($this->quizModel);
        if (!$canTake) {
            $errorMessage = 'Quiz này chưa đến thời gian làm bài, đã hết hạn hoặc bạn đã hết lượt làm bài.';

            Notification::make()
                ->title('Không thể làm bài!')
                ->body($errorMessage)
                ->danger()
                ->send();
            $this->redirect(route('filament.app.pages.my-quiz'));

            return;
        }

        $this->initializeQuizAttempt();
        $this->loadExistingAnswers();
        $this->calculateRemainingTime();

        // Force Livewire to re-render after loading answers
        $this->dispatch('answers-loaded');
    }

    protected function initializeQuizAttempt(): void
    {
        // Check for existing in-progress attempt
        $this->attempt = QuizAttempt::where('quiz_id', $this->quizModel->id)
            ->where('student_id', Auth::id())
            ->where('status', QuizAttemptStatus::IN_PROGRESS->value)
            ->first();

        // If no existing attempt, create new one
        if (! $this->attempt) {
            try {
                $this->attempt = $this->quizService->startQuizAttempt($this->quizModel->id, Auth::id());
                // Clear any previous localStorage for this quiz when starting new attempt
                $this->dispatch('clear-previous-quiz-storage', ['quizId' => $this->quizModel->id]);
            } catch (\Exception $e) {
                Notification::make()
                    ->title('Lỗi!')
                    ->body($e->getMessage())
                    ->danger()
                    ->send();
                // Sửa lại route cho đúng panel 'app'
                $this->redirect(route('filament.app.pages.my-quiz'));

                return;
            }
        }
    }

    protected function loadExistingAnswers(): void
    {
        if ($this->attempt) {
            $existingAnswers = [];

            // Group answers by question_id to handle multiple choice
            $answersByQuestion = $this->attempt->studentAnswers()
                ->get()
                ->groupBy('question_id');

            foreach ($answersByQuestion as $questionId => $answers) {
                // Check if this question is multiple response
                $question = $this->quizModel->questions->where('id', $questionId)->first();

                if ($question && $question->is_multiple_response) {
                    // Multiple choice - always store as array
                    $existingAnswers[$questionId] = $answers->pluck('answer_choice_id')->toArray();
                } else {
                    // Single choice - store as single value
                    $existingAnswers[$questionId] = $answers->first()->answer_choice_id;
                }
            }

            $this->answers = $existingAnswers;
            $this->updateAnsweredCount();
        }
    }

    protected function calculateRemainingTime(): void
    {
        if (! $this->quizModel->time_limit || ! $this->attempt) {
            $this->isUnlimited = true;

            return;
        }

        $this->isUnlimited = false;
        $startTime = Carbon::parse($this->attempt->start_at);
        $timeLimit = $this->quizModel->time_limit; // in minutes
        $endTime = $startTime->addMinutes($timeLimit);
        $now = Carbon::now();

        $this->remainingSeconds = max(0, $endTime->diffInSeconds($now, false));
        $this->timeWarning = $this->remainingSeconds <= 300; // 5 minutes warning

        // Auto-submit if time is up
        if ($this->remainingSeconds <= 0) {
            $this->autoSubmit();
        }
    }

    public function updateAnswer(string $questionId, string $choiceId): void
    {
        // Debug log để kiểm tra dữ liệu đầu vào
        Log::info('updateAnswer called', [
            'questionId' => $questionId,
            'choiceId' => $choiceId,
            'questionId_type' => gettype($questionId),
            'choiceId_type' => gettype($choiceId),
            'current_answers' => $this->answers,
        ]);

        // Get the question to check if it's multiple response
        $question = $this->quizModel->questions->where('id', $questionId)->first();

        if ($question && $question->is_multiple_response) {
            // Handle multiple choice - toggle the choice
            if (! isset($this->answers[$questionId])) {
                $this->answers[$questionId] = [];
            }

            if (! is_array($this->answers[$questionId])) {
                $this->answers[$questionId] = [];
            }

            $currentAnswers = $this->answers[$questionId];
            $choiceIndex = array_search($choiceId, $currentAnswers);

            if ($choiceIndex !== false) {
                // Remove choice if already selected
                unset($currentAnswers[$choiceIndex]);
                $this->answers[$questionId] = array_values($currentAnswers);
            } else {
                // Add choice if not selected
                $this->answers[$questionId][] = $choiceId;
            }
        } else {
            // Handle single choice - replace the answer
            $this->answers[$questionId] = $choiceId;
        }

        $this->updateAnsweredCount();
        $this->autoSave();
    }

    protected function updateAnsweredCount(): void
    {
        $answeredCount = 0;
        foreach ($this->answers as $questionId => $answer) {
            if (is_array($answer)) {
                // Multiple choice - count if has at least one selection
                if (! empty($answer)) {
                    $answeredCount++;
                }
            } else {
                // Single choice - count if not null
                if ($answer !== null) {
                    $answeredCount++;
                }
            }
        }
        $this->answeredCount = $answeredCount;
    }

    public function autoSave(): void
    {
        try {
            if ($this->attempt) {
                // Transform answers format for saveQuizAnswers method
                $transformedAnswers = [];
                foreach ($this->answers as $questionId => $answerData) {
                    if (is_array($answerData)) {
                        // Multiple choice answers
                        foreach ($answerData as $choiceId) {
                            $transformedAnswers[] = [
                                'question_id' => $questionId,
                                'answer_choice_id' => $choiceId,
                            ];
                        }
                    } else {
                        // Single choice answer
                        $transformedAnswers[] = [
                            'question_id' => $questionId,
                            'answer_choice_id' => $answerData,
                        ];
                    }
                }

                $this->quizService->saveQuizAnswers($this->attempt, $transformedAnswers);
            }
        } catch (\Exception $e) {
            Log::error('Auto-save failed', [
                'attempt_id' => $this->attempt?->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function submitQuiz(): void
    {
        if ($this->submitting) {
            return;
        }

        $this->submitting = true;

        try {
            // Debug log để kiểm tra dữ liệu trước khi submit
            Log::info('submitQuiz called', [
                'attempt_id' => $this->attempt->id,
                'answers' => $this->answers,
                'answers_structure' => array_map(function ($answer) {
                    return [
                        'type' => gettype($answer),
                        'value' => $answer,
                        'is_array' => is_array($answer),
                        'is_empty' => empty($answer),
                    ];
                }, $this->answers),
            ]);

            // Submit the quiz with original answers format (key-value)
            $this->quizService->submitQuizAttempt($this->attempt->id, $this->answers);

            // Clear localStorage after successful submission
            $this->dispatch('clear-quiz-storage');

            Notification::make()
                ->title('Thành công!')
                ->body('Bài quiz đã được nộp thành công.')
                ->success()
                ->send();

            // Redirect to results page
            // Giả sử bạn có một trang quiz-results
            // $this->redirect(route('filament.app.pages.quiz-results', ['attempt' => $this->attempt->id]));

            // Nếu chưa có trang kết quả, chuyển về trang MyQuiz
            $this->redirect(route('filament.app.pages.my-quiz'));
        } catch (\Exception $e) {
            $this->submitting = false;
            Notification::make()
                ->title('Lỗi!')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function autoSubmit(): void
    {
        Notification::make()
            ->title('Hết thời gian!')
            ->body('Bài quiz sẽ được nộp tự động.')
            ->warning()
            ->send();

        $this->submitQuiz();
    }

    #[Computed]
    public function formatTime(): string
    {
        if ($this->isUnlimited || $this->remainingSeconds === null) {
            return 'Không giới hạn';
        }

        $hours = floor($this->remainingSeconds / 3600);
        $minutes = floor(($this->remainingSeconds % 3600) / 60);
        $seconds = $this->remainingSeconds % 60;

        if ($hours > 0) {
            return sprintf('%d:%02d:%02d', $hours, $minutes, $seconds);
        }

        return sprintf('%02d:%02d', $minutes, $seconds);
    }

    #[Computed]
    public function progressPercentage(): int
    {
        if (! $this->quizModel || $this->quizModel->questions->count() === 0) {
            return 0;
        }

        return round(($this->answeredCount / $this->quizModel->questions->count()) * 100);
    }

    /**
     * Check if user can take the quiz
     * Simplified logic: check student role, quiz exists in course_quizzes with valid time
     */
    public function canTakeQuiz(Quiz $quiz): bool
    {
        $user = Auth::user();
        $userId = $user->id;

        // Check if user is a student
        if (!RoleHelper::isStudent($user)) {
            return false;
        }

        // Check if quiz exists in course_quizzes table with valid time restrictions
        $courseQuiz = $quiz->courses()
            ->withPivot(['start_at', 'end_at'])
            ->first();

        if (!$courseQuiz) {
            return false;
        }

        // Check time restrictions
        $now = now();
        $startAt = $courseQuiz->pivot->start_at;
        $endAt = $courseQuiz->pivot->end_at;

        // If start_at is set and current time is before start time
        if ($startAt && $now->lt($startAt)) {
            return false;
        }

        // If end_at is set and current time is after end time
        if ($endAt && $now->gt($endAt)) {
            return false;
        }

        // Check remaining attempts
        $remainingAttempts = $this->getQuizRemainingAttempts($quiz);
        // If remainingAttempts is null, it means unlimited attempts
        return $remainingAttempts === null || $remainingAttempts > 0;
    }

    /**
     * Get quiz status for display
     */
    public function getQuizStatus(Quiz $quiz): array
    {
        $userId = Auth::id();

        // Check if quiz is published
        if ($quiz->status !== QuizStatus::PUBLISHED->value) {
            return [
                'status' => 'not_published',
                'canTake' => false,
                'canViewResults' => false,
                'message' => 'Quiz chưa được xuất bản'
            ];
        }

        // Get user's enrolled courses with quiz time restrictions
        $userCourses = $quiz->courses()
            ->whereHas('users', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->withPivot(['start_at', 'end_at'])
            ->get();

        if ($userCourses->isEmpty()) {
            return [
                'status' => 'no_access',
                'canTake' => false,
                'canViewResults' => false,
                'message' => 'Bạn chưa đăng ký khóa học này'
            ];
        }

        // Check time restrictions
        $now = now();
        $canTakeInAnyCourse = false;

        foreach ($userCourses as $course) {
            $startAt = $course->pivot->start_at;
            $endAt = $course->pivot->end_at;

            // If no time restrictions, allow access
            if (!$startAt && !$endAt) {
                $canTakeInAnyCourse = true;
                break;
            }

            // Check if current time is within allowed range
            if ((!$startAt || $now->gte($startAt)) && (!$endAt || $now->lte($endAt))) {
                $canTakeInAnyCourse = true;
                break;
            }
        }

        if (!$canTakeInAnyCourse) {
            return [
                'status' => 'time_restricted',
                'canTake' => false,
                'canViewResults' => false,
                'message' => 'Chưa đến thời gian làm bài hoặc đã hết hạn'
            ];
        }

        // Check remaining attempts
        $remainingAttempts = $this->getQuizRemainingAttempts($quiz);

        // If remainingAttempts is not null and <= 0, user has reached max attempts
        if ($remainingAttempts !== null && $remainingAttempts <= 0) {
            return [
                'status' => 'max_attempts_reached',
                'canTake' => false,
                'canViewResults' => true,
                'message' => 'Đã hết lượt làm bài'
            ];
        }

        return [
            'status' => 'available',
            'canTake' => true,
            'canViewResults' => false,
            'message' => 'Có thể làm bài'
        ];
    }

    /**
     * Get remaining attempts for quiz
     */
    public function getQuizRemainingAttempts(Quiz $quiz): ?int
    {
        $userId = Auth::id();
        $maxAttempts = $quiz->max_attempts;
        
        // If max_attempts is 0 or null, allow unlimited attempts
        if ($maxAttempts === 0 || $maxAttempts === null) {
            return null; // null indicates unlimited attempts
        }
        
        $completedAttempts = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('student_id', $userId)
            ->whereIn('status', [QuizAttemptStatus::COMPLETED->value, QuizAttemptStatus::GRADED->value])
            ->count();

        return max(0, $maxAttempts - $completedAttempts);
    }

    public function getActions(): array
    {
        return [
            Action::make('back')
                ->label('Quay lại')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(route('filament.app.pages.my-quiz')),

            Action::make('submit')
                ->label('Nộp bài')
                ->icon('heroicon-o-paper-airplane')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Xác nhận nộp bài')
                ->modalDescription('Bạn có chắc chắn muốn nộp bài? Bạn không thể thay đổi sau khi nộp.')
                ->action(fn() => $this->submitQuiz())
                ->disabled(fn() => $this->submitting),
        ];
    }
}
