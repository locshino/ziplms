<?php

namespace App\Filament\Pages;

use App\Models\Course;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Services\Interfaces\QuizServiceInterface;
use App\Services\QuizAccessService;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MyQuiz extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static string $view = 'filament.pages.my-quiz';

    protected static ?string $navigationLabel = 'Quiz của tôi';

    protected static ?string $title = 'Quiz của tôi';

    public ?array $currentAnswers = [];

    public ?Quiz $selectedQuiz = null;

    public ?QuizAttempt $currentAttempt = null;

    public ?string $selectedCourseId = null;

    public ?string $selectedStatus = null;

    protected QuizServiceInterface $quizService;

    protected QuizAccessService $quizAccessService;

    public function boot(QuizServiceInterface $quizService, QuizAccessService $quizAccessService): void
    {
        $this->quizService = $quizService;
        $this->quizAccessService = $quizAccessService;
    }

    protected function canTakeQuiz(Quiz $quiz): bool
    {
        try {
            return $this->quizService->canTakeQuiz($quiz->id, Auth::id());
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getQuizzes()
    {
        $user = Auth::user();

        if ($user->hasRole('teacher')) {
            // Teacher: lấy quiz từ các khóa học mà họ dạy
            $quizzes = Quiz::with(['course', 'questions', 'attempts' => function ($query) {
                $query->where('student_id', Auth::id())
                    ->orderBy('created_at', 'desc');
            }])
                ->whereHas('course', function ($query) {
                    $query->where('teacher_id', Auth::id());
                });

            // Lọc theo khóa học nếu được chọn
            if ($this->selectedCourseId) {
                $quizzes->where('course_id', $this->selectedCourseId);
            }

            $quizzes = $quizzes->get();
        } else {
            // Student: sử dụng QuizService để lấy quiz từ các khóa học đã enrolled
            $quizzes = $this->quizService->getAvailableQuizzes(Auth::id());

            // Lọc theo khóa học nếu được chọn
            if ($this->selectedCourseId) {
                $quizzes = $quizzes->where('course_id', $this->selectedCourseId);
            }
        }

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

    public function getUserCourses()
    {
        $user = Auth::user();

        // Nếu user là teacher, hiển thị các khóa học mà họ dạy
        if ($user->hasRole('teacher')) {
            return Course::where('teacher_id', Auth::id())
                ->orderBy('title')
                ->get();
        }

        // Nếu user là student, chỉ hiển thị các khóa học mà họ đã enrolled
        return Course::whereHas('enrollments', function ($query) {
            $query->where('student_id', Auth::id());
        })
            ->orderBy('title')
            ->get();
    }

    public function updatedSelectedCourseId()
    {
        // Tự động cập nhật danh sách quiz khi thay đổi khóa học
        // Clear any cached data if needed
        $this->dispatch('quiz-list-updated');
    }

    public function updatedSelectedStatus()
    {
        // Tự động cập nhật danh sách quiz khi thay đổi trạng thái
        $this->dispatch('quiz-list-updated');
    }

    /**
     * Get quiz statistics using QuizService
     */
    public function getQuizStatistics(Quiz $quiz): array
    {
        return $this->quizService->getQuizStats($quiz->id);
    }

    /**
     * Get student's best score for a specific quiz
     */
    public function getStudentQuizBestScore(Quiz $quiz): ?float
    {
        return $this->quizService->getStudentBestScore($quiz->id, Auth::id());
    }

    /**
     * Get remaining attempts for a quiz
     */
    public function getQuizRemainingAttempts(Quiz $quiz): ?int
    {
        return $this->quizService->getRemainingAttempts($quiz->id, Auth::id());
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
        // Sử dụng logic từ QuizService để đếm quiz đã hoàn thành
        $completedQuizzes = collect();

        foreach ($this->getQuizzes() as $quiz) {
            $attempts = $this->quizService->getStudentAttempts($quiz->id, Auth::id());
            if ($attempts->where('status', 'completed')->isNotEmpty()) {
                $completedQuizzes->push($quiz->id);
            }
        }

        return $completedQuizzes->unique()->count();
    }

    public function getHighestScore(): float
    {
        $highestScore = 0;

        // Sử dụng QuizService để lấy điểm cao nhất từ tất cả quiz
        foreach ($this->getQuizzes() as $quiz) {
            $bestScore = $this->quizService->getStudentBestScore($quiz->id, Auth::id());
            if ($bestScore && $bestScore > $highestScore) {
                $highestScore = $bestScore;
            }
        }

        return round($highestScore, 1);
    }

    public function getQuizStatus(Quiz $quiz): array
    {
        $user = Auth::user();

        // Sử dụng QuizService để kiểm tra quyền truy cập
        $canTake = $this->quizService->canTakeQuiz($quiz->id, $user->id);
        $allAttempts = $this->quizService->getStudentAttempts($quiz->id, $user->id);
        $remainingAttempts = $this->quizService->getRemainingAttempts($quiz->id, $user->id);

        // Tách biệt các loại attempt
        $inProgressAttempt = $allAttempts->where('status', 'in_progress')->first();
        $completedAttempts = $allAttempts->whereIn('status', ['completed', 'submitted']);
        $latestCompletedAttempt = $completedAttempts->sortByDesc('created_at')->first();

        // Check if quiz is active
        if (! $quiz->isActive) {
            return [
                'status' => 'inactive',
                'label' => 'Bị khóa',
                'color' => 'gray',
                'canTake' => false,
                'canViewResults' => false,
            ];
        }

        // Check if has incomplete attempt first - chỉ hiển thị "Tiếp tục làm bài" khi thực sự có attempt in_progress
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

        // Check if max attempts reached
        if ($remainingAttempts !== null && $remainingAttempts <= 0) {
            return [
                'status' => 'max_attempts_reached',
                'label' => 'Hết lượt làm bài',
                'color' => 'warning',
                'canTake' => false,
                'canViewResults' => $latestCompletedAttempt !== null,
                'attempt' => $latestCompletedAttempt,
            ];
        }

        // Check basic access permission using QuizService
        if (! $canTake && $allAttempts->isEmpty()) {
            return [
                'status' => 'no_access',
                'label' => 'Không có quyền',
                'color' => 'danger',
                'canTake' => false,
                'canViewResults' => false,
            ];
        }

        // Check if has completed attempts - chỉ hiển thị "Làm lại" khi có attempt completed và vẫn còn lượt
        if ($latestCompletedAttempt && $canTake) {
            return [
                'status' => 'completed',
                'label' => 'Làm lại',
                'color' => 'success',
                'canTake' => true,
                'canViewResults' => true,
                'attempt' => $latestCompletedAttempt,
            ];
        }

        // Can start new quiz
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
            ->action(function (array $arguments) {
                return $this->handleQuizAction(
                    function () use ($arguments) {
                        $quiz = Quiz::find($arguments['quiz']);

                        // Sử dụng QuizService để kiểm tra quyền
                        if (! $this->quizService->canTakeQuiz($quiz->id, Auth::id())) {
                            Notification::make()
                                ->title('Không thể làm bài')
                                ->body('Bạn không có quyền làm bài quiz này hoặc quiz đã hết hạn.')
                                ->danger()
                                ->send();

                            return null;
                        }

                        // Redirect to Filament quiz taking page
                        return redirect()->route('filament.app.pages.quiz-taking', ['quiz' => $quiz->id]);
                    },
                    'take_quiz',
                    ['quiz_id' => $arguments['quiz']]
                );
            })
            ->before(function (array $arguments) {
                $this->selectedQuiz = Quiz::with(['questions.answerChoices'])
                    ->find($arguments['quiz']);

                // Check for existing attempt
                $this->currentAttempt = QuizAttempt::where('quiz_id', $this->selectedQuiz->id)
                    ->where('student_id', Auth::id())
                    ->where('status', 'in_progress')
                    ->first();

                // Only load existing answers if there's an in-progress attempt
                // For new attempts (after completing previous ones), start fresh
                if ($this->currentAttempt) {
                    // Load existing answers only for in-progress attempts
                    $existingAnswers = $this->currentAttempt->answers()
                        ->pluck('answer_choice_id', 'question_id')
                        ->toArray();
                    $this->currentAnswers = $existingAnswers;
                } else {
                    // Reset answers for new attempts
                    $this->currentAnswers = [];
                }
            });
    }

    public function viewResultsAction(): Action
    {
        return Action::make('viewResults')
            ->label('Xem đáp án')
            ->icon('heroicon-o-chart-bar')
            ->color('success')
            ->url(function (array $arguments) {
                $quiz = Quiz::find($arguments['quiz']);
                $latestAttempt = $this->quizService->getLatestAttempt($quiz->id, Auth::id());

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
            ->modalHeading(fn (array $arguments) => 'Lịch sử làm bài - '.Quiz::find($arguments['quiz'])->title)
            ->modalContent(function (array $arguments) {
                $quiz = Quiz::find($arguments['quiz']);
                $attempts = $this->quizService->getStudentAttempts($quiz->id, Auth::id())
                    ->whereIn('status', ['completed', 'submitted'])
                    ->sortByDesc('completed_at');

                return view('filament.components.quiz-history-modal', [
                    'quiz' => $quiz,
                    'attempts' => $attempts,
                ]);
            })
            ->modalWidth('4xl')
            ->modalSubmitAction(false)
            ->modalCancelActionLabel('Đóng');
    }
}
