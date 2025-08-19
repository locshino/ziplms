<?php

namespace App\Filament\Pages;

use App\Enums\Status\QuizStatus;
use App\Models\Course;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MyQuiz extends Page
{
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-academic-cap';

    protected string $view = 'filament.pages.my-quiz';

    protected static ?string $navigationLabel = 'Quiz của tôi';

    protected static ?string $title = 'Quiz của tôi';

    public ?array $currentAnswers = [];

    public ?Quiz $selectedQuiz = null;

    public ?QuizAttempt $currentAttempt = null;

    public ?string $selectedCourseId = null;

    public ?string $selectedStatus = null;
    
    public ?array $listQuizStatus = null;

    public function mount(): void
    {
        $this->setListQuizStatus();
    }

    protected function canTakeQuiz(Quiz $quiz): bool
    {
        // Check if quiz is active
        $isQuizActive = $quiz->status == QuizStatus::PUBLISHED;
        if (! $isQuizActive) return false;

        // Check if quiz has attempts left
        $userId = Auth::id();
        $maxAttempts = $quiz->max_attempts ?? 1;
        $attempts = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('student_id', $userId)
            ->count();

        return $attempts < $maxAttempts;
    }

    public function getQuizzes()
    {
        $user = Auth::user();
        $userId = $user->id;

        // Get quizzes linked to courses where the user is enrolled (users relation)
        $quizzesQuery = Quiz::with(['courses', 'questions', 'attempts' => function ($query) use ($userId) {
            $query->where('student_id', $userId)
                ->orderBy('created_at', 'desc');
        }])
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

    public function getUserCourses()
    {
        $user = Auth::user();
        $userId = $user->id;
        // Return courses where the user is teacher OR enrolled as student
        return Course::where(function ($query) use ($userId) {
            $query->where('teacher_id', $userId)
                ->orWhereHas('users', function ($q) use ($userId) {
                    $q->where('users.id', $userId)->whereNull('users.deleted_at');
                });
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
        // Example: count attempts, average score
        $attempts = QuizAttempt::where('quiz_id', $quiz->id)->get();
        $avgScore = $attempts->avg('score');
        $maxScore = $attempts->max('score');
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
        return QuizAttempt::where('quiz_id', $quiz->id)
            ->where('student_id', $userId)
            ->max('score');
    }

    /**
     * Get remaining attempts for a quiz
     */
    public function getQuizRemainingAttempts(Quiz $quiz): ?int
    {
        $userId = Auth::id();
        $maxAttempts = $quiz->max_attempts ?? 1;
        $usedAttempts = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('student_id', $userId)
            ->count();
        return max(0, $maxAttempts - $usedAttempts);
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
                ->where('status', 'completed')
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
        $highestScore = 0;
        foreach ($this->getQuizzes() as $quiz) {
            $bestScore = QuizAttempt::where('quiz_id', $quiz->id)
                ->where('student_id', $userId)
                ->max('score');
            if ($bestScore && $bestScore > $highestScore) {
                $highestScore = $bestScore;
            }
        }
        return round($highestScore, 1);
    }

    public function getQuizStatus(Quiz $quiz): array
    {
        $userId = Auth::id();
        $canTake = $this->canTakeQuiz($quiz);
        $allAttempts = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('student_id', $userId)
            ->get();
        $remainingAttempts = $this->getQuizRemainingAttempts($quiz);
        $inProgressAttempt = $allAttempts->where('status', 'in_progress')->first();
        $completedAttempts = $allAttempts->whereIn('status', ['completed', 'submitted']);
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
            return [
                'status' => 'max_attempts_reached',
                'label' => 'Hết lượt làm bài',
                'color' => 'warning',
                'canTake' => false,
                'canViewResults' => $latestCompletedAttempt !== null,
                'attempt' => $latestCompletedAttempt,
            ];
        }
        if (! $canTake && $allAttempts->isEmpty()) {
            return [
                'status' => 'no_access',
                'label' => 'Không có quyền',
                'color' => 'danger',
                'canTake' => false,
                'canViewResults' => false,
            ];
        }
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
                        if (! $this->canTakeQuiz($quiz)) {
                            Notification::make()
                                ->title('Không thể làm bài')
                                ->body('Bạn không có quyền làm bài quiz này hoặc quiz đã hết hạn.')
                                ->danger()
                                ->send();
                            return null;
                        }
                        return redirect()->route('filament.app.pages.quiz-taking', ['quiz' => $quiz->id]);
                    },
                    'take_quiz',
                    ['quiz_id' => $arguments['quiz']]
                );
            })
            ->before(function (array $arguments) {
                $this->selectedQuiz = Quiz::with(['questions.answerChoices'])
                    ->find($arguments['quiz']);
                $this->currentAttempt = QuizAttempt::where('quiz_id', $this->selectedQuiz->id)
                    ->where('student_id', Auth::id())
                    ->where('status', 'in_progress')
                    ->first();
                if ($this->currentAttempt) {
                    $existingAnswers = $this->currentAttempt->answers()
                        ->pluck('answer_choice_id', 'question_id')
                        ->toArray();
                    $this->currentAnswers = $existingAnswers;
                } else {
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
                $latestAttempt = QuizAttempt::where('quiz_id', $quiz->id)
                    ->where('student_id', Auth::id())
                    ->whereIn('status', ['completed', 'submitted'])
                    ->orderByDesc('completed_at')
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
            ->modalHeading(fn (array $arguments) => 'Lịch sử làm bài - '.Quiz::find($arguments['quiz'])->title)
            ->modalContent(function (array $arguments) {
                $quiz = Quiz::find($arguments['quiz']);
                $attempts = QuizAttempt::where('quiz_id', $quiz->id)
                    ->where('student_id', Auth::id())
                    ->whereIn('status', ['completed', 'submitted'])
                    ->orderByDesc('completed_at')
                    ->get();
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
