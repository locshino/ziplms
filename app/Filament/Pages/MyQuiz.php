<?php

namespace App\Filament\Pages;

use App\Enums\Status\QuizStatus;
use App\Models\Course;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Services\Interfaces\QuizFilterServiceInterface;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Enums\Status\QuizAttemptStatus;
use Livewire\Attributes\Computed;
// use BezhanSalleh\FilamentShield\Traits\HasPageShield;

class MyQuiz extends Page
{
    // use HasPageShield;
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-academic-cap';

    protected string $view = 'filament.pages.my-quiz';

    protected static ?string $navigationLabel = 'Quiz của tôi';

    protected static ?string $title = 'Quiz của tôi';

    public ?array $currentAnswers = [];

    public ?Quiz $selectedQuiz = null;

    public ?QuizAttempt $currentAttempt = null;

    public bool $submitting = false;

    public ?string $selectedCourseId = null;

    public ?string $selectedStatus = null;

    public ?array $listQuizStatus = null;

    public string $selectedFilter = 'all';

    public string $searchTerm = '';

    // Pagination properties
    public int $perPage = 10;

    public int $currentPage = 1;



    public function mount(): void
    {
        $this->selectedFilter = request('filter', 'all');
        $this->selectedCourseId = request('course_id', null);
        $this->searchTerm = request('search', '');
        $this->setListQuizStatus();
    }

    protected function canTakeQuiz(Quiz $quiz): bool
    {
        $userId = Auth::id();
        $now = now();

        // Check if quiz is published
        if ($quiz->status !== QuizStatus::PUBLISHED) {
            return false;
        }

        // Check course_quizzes timing for each course the user is enrolled in
        $canTakeInAnyCourse = false;

        // Get courses where user is enrolled and quiz is assigned
        $userCourses = $quiz->courses()->whereHas('users', function ($q) use ($userId) {
            $q->where('users.id', $userId);
        })->get();

        foreach ($userCourses as $course) {
            $courseQuiz = $course->pivot; // This is the CourseQuiz model

            // Check if quiz timing is valid for this course
            $startAt = $courseQuiz->start_at;
            $endAt = $courseQuiz->end_at;

            // If no timing restrictions, or within valid time range
            if ((!$startAt || $now->gte($startAt)) && (!$endAt || $now->lte($endAt))) {
                $canTakeInAnyCourse = true;
                break;
            }
        }

        if (!$canTakeInAnyCourse) {
            return false;
        }

        // Check if quiz has attempts left
        $maxAttempts = $quiz->max_attempts;
        $attempts = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('student_id', $userId)
            ->count();

        // If max_attempts is 0 or null, unlimited attempts allowed
        if ($maxAttempts === 0 || $maxAttempts === null) {
            return true;
        }

        // Check if user has remaining attempts
        return $attempts < $maxAttempts;
    }

    public function getQuizzes()
    {
        $user = Auth::user();
        $userId = $user->id;

        // Get quizzes linked to courses where the user is enrolled (users relation)
        $quizzesQuery = Quiz::with([
            'courses' => function ($query) use ($userId) {
                $query->whereHas('users', function ($q) use ($userId) {
                    $q->where('users.id', $userId);
                })->withPivot('start_at', 'end_at');
            },
            'questions',
            'attempts' => function ($query) use ($userId) {
                $query->where('student_id', $userId)
                    ->orderBy('created_at', 'desc');
            }
        ])
            ->whereHas('courses.users', function ($q) use ($userId) {
                $q->where('users.id', $userId);
            });

        // If course filter selected, narrow down quizzes to that course
        if ($this->selectedCourseId) {
            $quizzesQuery->whereHas('courses', function ($query) {
                $query->where('id', $this->selectedCourseId);
            });
        }

        $quizzes = $quizzesQuery->get();

        // Filter by status if selected
        if ($this->selectedStatus) {
            $quizzes = $quizzes->filter(function ($quiz) {
                $quizStatus = $this->getQuizStatus($quiz);
                switch ($this->selectedStatus) {
                    case 'completed':
                        return in_array($quizStatus['status'], ['completed', 'max_attempts_reached']) && $quizStatus['canViewResults'];
                    case 'available':
                        return $quizStatus['status'] === 'available' && $quizStatus['canTake'];
                    case 'in_progress':
                        return $quizStatus['status'] === 'in_progress';
                    default:
                        return true;
                }
            });
        }
        return $quizzes->sortByDesc('created_at');
    }

    public function setListQuizStatus(?array $listQuizStatus = null)
    {
        if ($listQuizStatus != null) {
            $this->listQuizStatus = $listQuizStatus;
        }

        $caseStatus = QuizStatus::cases();
        $listQuizStatus = [];

        foreach ($caseStatus as $status) {
            /** @var QuizStatus $status */

            $listQuizStatus[] = [
                'value' => $status->value,
                'label' => $status->getLabel(),
            ];
        }

        $this->listQuizStatus = $listQuizStatus;

        return;
    }



    public function updatedSelectedStatus(): void
    {
        // Tự động cập nhật danh sách quiz khi thay đổi trạng thái
        $this->resetToFirstPage();
        $this->dispatch('quiz-list-updated');
    }

    /**
     * Get quiz statistics using QuizService
     */
    public function getQuizStatistics(Quiz $quiz): array
    {
        // Example: count attempts, average score
        $attempts = QuizAttempt::where('quiz_id', $quiz->id)->get();
        $avgScore = $attempts->avg('points');
        $maxScore = $attempts->max('points');
        $count = $attempts->count();
        return [
            'attempts_count' => $count,
            'avg_score' => $avgScore,
            'max_score' => $maxScore,
        ];
    }

    /**
     * Get student's best score for a specific quiz
     */
    public function getStudentQuizBestScore(Quiz $quiz): ?float
    {
        $userId = Auth::id();

        // Get the max score from the database (could be a string or null)
        $maxScore = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('student_id', Auth::id())
            ->max('points');

        // If a score was found, cast it to a float. Otherwise, return null.
        return $maxScore === null ? null : (float) $maxScore;
    }

    /**
     * Get remaining attempts for a quiz
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

    /**
     * Handle quiz action with proper error handling and logging
     */
    protected function handleQuizAction(callable $action, string $actionName, array $context = []): mixed
    {
        try {
            $result = $action();

            Log::info('Quiz action completed successfully', [
                'action' => $actionName,
                'user_id' => Auth::id(),
                'context' => $context,
            ]);

            return $result;
        } catch (\Exception $e) {
            Log::error('Quiz action failed', [
                'action' => $actionName,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'context' => $context,
            ]);

            Notification::make()
                ->title('Có lỗi xảy ra')
                ->body('Vui lòng thử lại sau hoặc liên hệ quản trị viên.')
                ->danger()
                ->send();

            return null;
        }
    }

    public function getCompletedQuizzesCount(): int
    {
        $userId = Auth::id();
        $completedQuizzes = collect();
        foreach ($this->getQuizzes() as $quiz) {
            $attempts = QuizAttempt::where('quiz_id', $quiz->id)
                ->where('student_id', $userId)
                ->where('status', QuizAttemptStatus::COMPLETED->value)
                ->count();
            if ($attempts > 0) {
                $completedQuizzes->push($quiz->id);
            }
        }
        return $completedQuizzes->unique()->count();
    }

    public function getHighestScore(): float
    {
        $userId = Auth::id();
        $highestPercentage = 0;
        foreach ($this->getQuizzes() as $quiz) {
            $bestScore = QuizAttempt::where('quiz_id', $quiz->id)
                ->where('student_id', Auth::id())
                ->max('points');
            if ($bestScore) {
                $maxPoints = $quiz->questions->sum('pivot.points');
                if ($maxPoints > 0) {
                    $percentage = ((float)$bestScore / $maxPoints) * 100;
                    // Ensure percentage doesn't exceed 100%
                    $percentage = min($percentage, 100);
                    if ($percentage > $highestPercentage) {
                        $highestPercentage = $percentage;
                    }
                }
            }
        }
        return round($highestPercentage, 1);
    }

    /**
     * Get average percentage score from all completed quizzes
     */
    public function getAveragePercentage(): float
    {
        $userId = Auth::id();
        $totalPercentage = 0;
        $completedQuizzes = 0;

        foreach ($this->getQuizzes() as $quiz) {
            $bestScore = QuizAttempt::where('quiz_id', $quiz->id)
                ->where('student_id', $userId)
                ->max('points');

            if ($bestScore !== null) {
                $maxPoints = $quiz->questions->sum('pivot.points');
                if ($maxPoints > 0) {
                    $percentage = ((float)$bestScore / $maxPoints) * 100;
                    // Ensure percentage doesn't exceed 100%
                    $percentage = min($percentage, 100);
                    $totalPercentage += $percentage;
                    $completedQuizzes++;
                }
            }
        }

        return $completedQuizzes > 0 ? round($totalPercentage / $completedQuizzes, 1) : 0;
    }



    public function getQuizStatus(Quiz $quiz): array
    {
        $userId = Auth::id();
        $canTake = $this->canTakeQuiz($quiz);

        $allAttempts = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('student_id', $userId)
            ->get();
        $remainingAttempts = $this->getQuizRemainingAttempts($quiz);
        $inProgressAttempt = $allAttempts->filter(function ($attempt) {
            return $attempt->status->value === QuizAttemptStatus::IN_PROGRESS->value;
        })->first();
        $completedAttempts = $allAttempts->filter(function ($attempt) {
            return in_array($attempt->status->value, [QuizAttemptStatus::COMPLETED->value, QuizAttemptStatus::GRADED->value]);
        });
        $latestCompletedAttempt = $completedAttempts->sortByDesc('created_at')->first();

        if (! $quiz->isActive) {
            return [
                'status' => 'inactive',
                'label' => 'Bị khóa',
                'color' => 'gray',
                'canTake' => false,
                'canViewResults' => false,
            ];
        }
        if ($inProgressAttempt) {
            return [
                'status' => 'in_progress',
                'label' => 'Tiếp tục làm bài',
                'color' => 'warning',
                'canTake' => true,
                'canViewResults' => false,
                'attempt' => $inProgressAttempt,
            ];
        }
        if ($remainingAttempts !== null && $remainingAttempts <= 0) {
            $label = $latestCompletedAttempt ? 'Xem đáp án' : 'Hết lượt làm bài';
            return [
                'status' => 'max_attempts_reached',
                'label' => $label,
                'color' => $latestCompletedAttempt ? 'success' : 'danger',
                'canTake' => false,
                'canViewResults' => $latestCompletedAttempt !== null,
                'attempt' => $latestCompletedAttempt,
            ];
        }
        if (! $canTake && $allAttempts->isEmpty()) {
            // Check specific reason for no access
            $now = now();
            $hasValidTiming = false;
            $isPublished = $quiz->status === QuizStatus::PUBLISHED->value;

            if ($isPublished) {
                // Check if any course has valid timing
                $userCourses = $quiz->courses()->whereHas('users', function ($q) use ($userId) {
                    $q->where('users.id', $userId);
                })->get();

                foreach ($userCourses as $course) {
                    $courseQuiz = $course->pivot;
                    $startAt = $courseQuiz->start_at;
                    $endAt = $courseQuiz->end_at;

                    if ((!$startAt || $now->gte($startAt)) && (!$endAt || $now->lte($endAt))) {
                        $hasValidTiming = true;
                        break;
                    }
                }
            }

            if (!$isPublished) {
                return [
                    'status' => 'not_published',
                    'label' => 'Chưa được xuất bản',
                    'color' => 'gray',
                    'canTake' => false,
                    'canViewResults' => false,
                ];
            } elseif (!$hasValidTiming) {
                return [
                    'status' => 'time_restricted',
                    'label' => 'Chưa đến thời gian làm bài',
                    'color' => 'warning',
                    'canTake' => false,
                    'canViewResults' => false,
                ];
            } else {
                return [
                    'status' => 'no_access',
                    'label' => 'Không có quyền',
                    'color' => 'danger',
                    'canTake' => false,
                    'canViewResults' => false,
                ];
            }
        }
        if ($latestCompletedAttempt && $canTake) {
            // If user has completed but still has attempts (or unlimited), show "Làm bài" instead of "Làm lại"
            $label = ($remainingAttempts === null || $remainingAttempts > 0) ? 'Làm bài' : 'Làm lại';
            return [
                'status' => 'completed',
                'label' => $label,
                'color' => 'success',
                'canTake' => true,
                'canViewResults' => true,
                'attempt' => $latestCompletedAttempt,
            ];
        }
        if ($latestCompletedAttempt && !$canTake) {
            return [
                'status' => 'completed_no_retake',
                'label' => 'Xem đáp án',
                'color' => 'success',
                'canTake' => false,
                'canViewResults' => true,
                'attempt' => $latestCompletedAttempt,
            ];
        }
        return [
            'status' => 'available',
            'label' => 'Làm bài ngay',
            'color' => 'primary',
            'canTake' => $canTake,
            'canViewResults' => $latestCompletedAttempt !== null,
            'attempt' => $latestCompletedAttempt,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function takeQuizAction(): Action
    {
        return Action::make('takeQuiz')
            ->label('Làm bài')
            ->icon('heroicon-o-play')
            ->color('primary')
            ->url(function (array $arguments) {
                $quiz = Quiz::find($arguments['quiz']);

                // Check if user can take the quiz before redirecting
                if (! $this->canTakeQuiz($quiz)) {
                    $quizStatus = $this->getQuizStatus($quiz);
                    $message = match ($quizStatus['status']) {
                        'not_published' => 'Quiz chưa được xuất bản.',
                        'time_restricted' => 'Chưa đến thời gian làm bài hoặc đã hết hạn.',
                        'max_attempts_reached' => 'Bạn đã hết lượt làm bài.',
                        default => 'Bạn không có quyền làm bài quiz này.'
                    };

                    // Show notification and return current page
                    Notification::make()
                        ->title('Không thể làm bài')
                        ->body($message)
                        ->danger()
                        ->send();

                    return null; // Stay on current page
                }

                return route('filament.app.pages.quiz-taking', ['quiz' => $arguments['quiz']]);
            })
            ->openUrlInNewTab(false);
    }

    public function viewResultsAction(): Action
    {
        return Action::make('viewResults')
            ->label('Xem đáp án')
            ->icon('heroicon-o-chart-bar')
            ->color('success')
            ->url(function (array $arguments) {
                $quiz = Quiz::find($arguments['quiz']);
                $latestAttempt = QuizAttempt::where('quiz_id', $quiz->id)
                    ->where('student_id', Auth::id())
                    ->whereIn('status', ['completed', 'submitted'])
                    ->orderByDesc('end_at')
                    ->first();
                $params = ['quiz' => $arguments['quiz']];
                if ($latestAttempt) {
                    $params['attempt_id'] = $latestAttempt->id;
                }
                return route('filament.app.pages.quiz-answers', $params);
            })
            ->openUrlInNewTab(false);
    }

    public function viewHistoryAction(): Action
    {
        return Action::make('viewHistory')
            ->label('Lịch sử làm bài')
            ->icon('heroicon-o-clock')
            ->color('gray')
            ->modalHeading(fn(array $arguments) => 'Lịch sử làm bài ')
            ->modalContent(function (array $arguments) {
                $quiz = Quiz::find($arguments['quiz']);
                $attempts = QuizAttempt::where('quiz_id', $quiz->id)
                    ->where('student_id', Auth::id())
                    ->whereIn('status', [QuizAttemptStatus::COMPLETED->value, QuizAttemptStatus::GRADED->value])
                    ->orderByDesc('end_at')
                    ->get();
                return view('filament.components.quiz-history-modal', [
                    'quiz' => $quiz,
                    'attempts' => $attempts,
                ]);
            })
            ->modalWidth('4xl')
            ->modalSubmitAction(false);
    }

    // Quiz taking methods
    public function initializeQuizAttempt()
    {
        if (!$this->selectedQuiz) {
            return;
        }

        try {
            // First check for existing in-progress attempt
            $this->currentAttempt = QuizAttempt::where('quiz_id', $this->selectedQuiz->id)
                ->where('student_id', Auth::id())
                ->where('status', QuizAttemptStatus::IN_PROGRESS->value)
                ->first();

            if (!$this->currentAttempt) {
                // Use QuizService to properly create attempt with all validations
                $quizService = app(\App\Services\Interfaces\QuizServiceInterface::class);
                $this->currentAttempt = $quizService->startQuizAttempt($this->selectedQuiz->id, Auth::id());
                $this->currentAnswers = [];
            } else {
                $existingAnswers = $this->currentAttempt->quizAnswers()
                    ->pluck('answer_choice_id', 'question_id')
                    ->toArray();
                $this->currentAnswers = $existingAnswers;
            }
        } catch (\Exception $e) {
            Notification::make()
                ->title('Lỗi!')
                ->body($e->getMessage())
                ->danger()
                ->send();
            return;
        }
    }

    public function updateAnswer($questionId, $choiceId)
    {
        if (!$this->currentAttempt) {
            return;
        }

        $question = $this->selectedQuiz->questions()->find($questionId);
        if (!$question) {
            return;
        }

        if ($question->is_multiple_response) {
            if (!isset($this->currentAnswers[$questionId])) {
                $this->currentAnswers[$questionId] = [];
            }

            if (in_array($choiceId, $this->currentAnswers[$questionId])) {
                $this->currentAnswers[$questionId] = array_diff($this->currentAnswers[$questionId], [$choiceId]);
            } else {
                $this->currentAnswers[$questionId][] = $choiceId;
            }
        } else {
            $this->currentAnswers[$questionId] = $choiceId;
        }

        $this->saveAnswer($questionId, $choiceId, $question->is_multiple_response);
    }

    public function saveAnswer($questionId, $choiceId, $isMultiple = false)
    {
        if (!$this->currentAttempt) {
            return;
        }

        if ($isMultiple) {
            // For multiple choice, delete existing answers and save new ones
            $this->currentAttempt->quizAnswers()->where('question_id', $questionId)->delete();

            if (isset($this->currentAnswers[$questionId]) && !empty($this->currentAnswers[$questionId])) {
                foreach ($this->currentAnswers[$questionId] as $choice) {
                    $this->currentAttempt->quizAnswers()->create([
                        'question_id' => $questionId,
                        'answer_choice_id' => $choice,
                    ]);
                }
            }
        } else {
            // For single choice, update or create
            $this->currentAttempt->quizAnswers()->updateOrCreate(
                ['question_id' => $questionId],
                ['answer_choice_id' => $choiceId]
            );
        }
    }

    public function confirmSubmission(): bool
    {
        // Use Filament's built-in confirmation dialog
        return $this->confirm(
            title: 'Xác nhận nộp bài',
            message: 'Bạn có chắc chắn muốn nộp bài? Bạn không thể thay đổi sau khi nộp.',
            icon: 'heroicon-o-question-mark-circle'
        );
    }

    public function showAutoSubmitNotification(): void
    {
        Notification::make()
            ->title('Hết thời gian!')
            ->body('Bài quiz sẽ được nộp tự động.')
            ->warning()
            ->send();
    }

    public function submitQuiz()
    {
        if (!$this->currentAttempt || $this->submitting) {
            return;
        }

        $this->submitting = true;

        try {
            // Calculate score
            $totalScore = 0;
            $maxScore = 0;

            foreach ($this->selectedQuiz->questions as $question) {
                $questionPoints = $question->pivot->points ?? 1;
                $maxScore += $questionPoints;

                $userAnswers = $this->currentAttempt->quizAnswers()
                    ->where('question_id', $question->id)
                    ->pluck('answer_choice_id')
                    ->toArray();

                $correctAnswers = $question->answerChoices()
                    ->where('is_correct', true)
                    ->pluck('id')
                    ->toArray();

                if (empty(array_diff($correctAnswers, $userAnswers)) && empty(array_diff($userAnswers, $correctAnswers))) {
                    $totalScore += $questionPoints;
                }
            }

            // Update attempt
            $this->currentAttempt->update([
                'status' => QuizAttemptStatus::COMPLETED->value,
                'end_at' => now(),
                'points' => $totalScore,
                'max_points' => $maxScore,
            ]);

            Notification::make()
                ->title('Nộp bài thành công!')
                ->body("Điểm của bạn: {$totalScore}/{$maxScore}")
                ->success()
                ->send();

            $this->selectedQuiz = null;
            $this->currentAttempt = null;
            $this->currentAnswers = [];
            $this->submitting = false;

            $this->dispatch('close-modal', id: 'takeQuiz');
        } catch (\Exception $e) {
            $this->submitting = false;
            Notification::make()
                ->title('Lỗi!')
                ->body('Có lỗi xảy ra khi nộp bài: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function backToQuizList()
    {
        $this->selectedQuiz = null;
        $this->currentAttempt = null;
        $this->currentAnswers = [];
        $this->dispatch('close-modal', id: 'takeQuiz');
    }

    public function getAnsweredCount()
    {
        if (!$this->currentAnswers) {
            return 0;
        }

        return count(array_filter($this->currentAnswers, function ($answer) {
            return is_array($answer) ? !empty($answer) : $answer !== null;
        }));
    }

    public function getProgressPercentage()
    {
        if (!$this->selectedQuiz) {
            return 0;
        }

        $totalQuestions = $this->selectedQuiz->questions->count();
        if ($totalQuestions === 0) {
            return 0;
        }

        return round(($this->getAnsweredCount() / $totalQuestions) * 100);
    }

    /**
     * Lấy thống kê quiz đã lọc từ QuizFilterService
     */
    public function getFilteredQuizStatistics(): array
    {
        // QuizFilterService đã được inject qua dependency injection

        // Nếu có bộ lọc khóa học hoặc tìm kiếm, tính toán thống kê dựa trên kết quả lọc
        if ($this->selectedCourseId || !empty($this->searchTerm)) {
            $allQuizzes = app(QuizFilterServiceInterface::class)->getAllQuizzes();
        $filteredQuizzes = app(QuizFilterServiceInterface::class)->applyAllFilters(
            $allQuizzes,
            $this->selectedCourseId,
            $this->searchTerm
        );

        $unsubmittedQuizzes = app(QuizFilterServiceInterface::class)->applyAllFilters(
            app(QuizFilterServiceInterface::class)->getUnsubmittedQuizzes(),
            $this->selectedCourseId,
            $this->searchTerm
        );
        $overdueQuizzes = app(QuizFilterServiceInterface::class)->applyAllFilters(
            app(QuizFilterServiceInterface::class)->getOverdueQuizzes(),
            $this->selectedCourseId,
            $this->searchTerm
        );
        $submittedQuizzes = app(QuizFilterServiceInterface::class)->applyAllFilters(
            app(QuizFilterServiceInterface::class)->getSubmittedQuizzes(),
            $this->selectedCourseId,
            $this->searchTerm
        );

            return [
                'total' => $filteredQuizzes->count(),
                'unsubmitted' => $unsubmittedQuizzes->count(),
                'overdue' => $overdueQuizzes->count(),
                'submitted' => $submittedQuizzes->count(),
            ];
        }

        // Nếu không có bộ lọc, trả về thống kê tổng
        return app(QuizFilterServiceInterface::class)->getQuizStatistics();
    }

    /**
     * Lấy danh sách quiz đã lọc từ QuizFilterService
     */
    public function getFilteredQuizzes()
    {
        // QuizFilterService đã được inject qua dependency injection

        // Lấy quiz theo trạng thái


        $quizzes = match ($this->selectedFilter) {
            'unsubmitted' => app(QuizFilterServiceInterface::class)->getUnsubmittedQuizzes(),
            'overdue' => app(QuizFilterServiceInterface::class)->getOverdueQuizzes(),
            'submitted' => app(QuizFilterServiceInterface::class)->getSubmittedQuizzes(),
            'retakeable' => app(QuizFilterServiceInterface::class)->getRetakeableQuizzes(),
            default => app(QuizFilterServiceInterface::class)->getAllQuizzes(),
        };

        // Áp dụng các bộ lọc khác (khóa học và tìm kiếm)
        $quizzes = app(QuizFilterServiceInterface::class)->applyAllFilters(
            $quizzes,
            $this->selectedCourseId,
            $this->searchTerm
        );

        // Áp dụng phân trang
        $offset = ($this->currentPage - 1) * $this->perPage;
        return $quizzes->skip($offset)->take($this->perPage);
    }

    /**
     * Lấy tổng số quiz (không phân trang) để tính toán phân trang
     */
    public function getTotalQuizzesCount(): int
    {
        // QuizFilterService đã được inject qua dependency injection

        // Lấy quiz theo trạng thái
        $quizzes = match ($this->selectedFilter) {
            'unsubmitted' => app(QuizFilterServiceInterface::class)->getUnsubmittedQuizzes(),
            'overdue' => app(QuizFilterServiceInterface::class)->getOverdueQuizzes(),
            'submitted' => app(QuizFilterServiceInterface::class)->getSubmittedQuizzes(),
            'retakeable' => app(QuizFilterServiceInterface::class)->getRetakeableQuizzes(),
            default => app(QuizFilterServiceInterface::class)->getAllQuizzes(),
        };

        // Áp dụng các bộ lọc khác (khóa học và tìm kiếm)
        $quizzes = app(QuizFilterServiceInterface::class)->applyAllFilters(
            $quizzes,
            $this->selectedCourseId,
            $this->searchTerm
        );

        return $quizzes->count();
    }

    /**
     * Cập nhật bộ lọc
     */
    public function updateFilter(string $filter): void
    {
        $this->selectedFilter = $filter;
    }

    /**
     * Cập nhật bộ lọc khóa học
     */
    public function updateCourseFilter(?string $courseId): void
    {
        $this->selectedCourseId = $courseId;
    }

    /**
     * Cập nhật từ khóa tìm kiếm
     */
    public function updateSearchTerm(string $searchTerm): void
    {
        $this->searchTerm = $searchTerm;
    }

    /**
     * Xóa tất cả bộ lọc
     */
    public function clearAllFilters(): void
    {
        $this->selectedFilter = 'all';
        $this->selectedCourseId = null;
        $this->searchTerm = '';
    }

    /**
     * Lấy danh sách khóa học của user
     */
    public function getUserCourses()
    {
        return Course::whereHas('users', function ($query) {
            $query->where('users.id', Auth::id());
        })->orderBy('title')->get();
    }

    /**
     * Tính tổng số trang
     */
    #[Computed]
    public function getTotalPages(): int
    {
        $totalQuizzes = $this->getTotalQuizzesCount();
        return (int) ceil($totalQuizzes / $this->perPage);
    }

    /**
     * Kiểm tra có trang tiếp theo không
     */
    #[Computed]
    public function hasNextPage(): bool
    {
        return $this->currentPage < $this->getTotalPages();
    }

    /**
     * Kiểm tra có trang trước không
     */
    #[Computed]
    public function hasPreviousPage(): bool
    {
        return $this->currentPage > 1;
    }

    /**
     * Lấy thông tin phân trang để hiển thị
     */
    #[Computed]
    public function getPaginationInfo(): array
    {
        $totalQuizzes = $this->getTotalQuizzesCount();
        $totalPages = $this->getTotalPages();
        $startItem = ($this->currentPage - 1) * $this->perPage + 1;
        $endItem = min($this->currentPage * $this->perPage, $totalQuizzes);

        return [
            'current_page' => $this->currentPage,
            'total_pages' => $totalPages,
            'total_items' => $totalQuizzes,
            'start_item' => $startItem,
            'end_item' => $endItem,
            'per_page' => $this->perPage,
            'has_next' => $this->hasNextPage(),
            'has_previous' => $this->hasPreviousPage(),
        ];
    }

    /**
     * Lấy trạng thái chi tiết của quiz từ service
     */
    public function getQuizDetailedStatusFromService(Quiz $quiz): array
    {
        return app(QuizFilterServiceInterface::class)->getQuizDetailedStatus($quiz);
    }

    /**
     * Chuyển đến trang tiếp theo
     */
    public function nextPage(): void
    {
        if ($this->currentPage < $this->getTotalPages()) {
            $this->currentPage++;
        }
    }

    /**
     * Chuyển đến trang trước
     */
    public function previousPage(): void
    {
        if ($this->currentPage > 1) {
            $this->currentPage--;
        }
    }

    /**
     * Chuyển đến trang cụ thể
     */
    public function goToPage(int $page): void
    {
        if ($page >= 1 && $page <= $this->getTotalPages()) {
            $this->currentPage = $page;
        }
    }

    /**
     * Reset về trang đầu khi thay đổi bộ lọc
     */
    public function resetToFirstPage(): void
    {
        $this->currentPage = 1;
    }

    /**
     * Override các phương thức update để reset về trang đầu
     */
    public function updatedSelectedFilter(): void
    {
        $this->resetToFirstPage();
    }

    public function updatedSelectedCourseId(): void
    {
        $this->resetToFirstPage();
        $this->dispatch('quiz-list-updated');
    }

    public function updatedSearchTerm(): void
    {
        $this->resetToFirstPage();
    }
}
