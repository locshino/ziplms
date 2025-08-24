<?php

namespace App\Services;

use App\Enums\Status\QuizAttemptStatus;
use App\Enums\Status\QuizStatus;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

/**
 * Service xử lý logic lọc dữ liệu quiz
 * Cung cấp các phương thức lọc quiz theo trạng thái khác nhau
 */
class QuizFilterService
{
    /**
     * Lấy tất cả quiz của user với thông tin chi tiết
     */
    public function getAllQuizzes(?User $user = null): Collection
    {
        $user = $user ?? Auth::user();
        $userId = $user->id;

        return Quiz::with([
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
        })
        ->where('status', QuizStatus::PUBLISHED)
        ->get();
    }

    /**
     * Lọc quiz chưa nộp (chưa có attempt hoặc có attempt in_progress)
     */
    public function getUnsubmittedQuizzes(?User $user = null): Collection
    {
        $allQuizzes = $this->getAllQuizzes($user);
        $user = $user ?? Auth::user();
        $userId = $user->id;

        return $allQuizzes->filter(function ($quiz) use ($userId) {
            $attempts = $quiz->attempts;
            
            // Nếu không có attempt nào
            if ($attempts->isEmpty()) {
                return $this->canTakeQuiz($quiz, $userId);
            }

            // Nếu có attempt in_progress
            $inProgressAttempt = $attempts->where('status', QuizAttemptStatus::IN_PROGRESS)->first();
            if ($inProgressAttempt) {
                return true;
            }

            // Nếu tất cả attempts đều completed/graded và còn lượt làm
            $completedAttempts = $attempts->whereIn('status', [
                QuizAttemptStatus::COMPLETED,
                QuizAttemptStatus::GRADED
            ]);
            
            if ($completedAttempts->count() > 0) {
                return $this->hasRemainingAttempts($quiz, $userId);
            }

            return false;
        });
    }

    /**
     * Lọc quiz quá hạn (đã hết thời gian làm bài)
     */
    public function getOverdueQuizzes(?User $user = null): Collection
    {
        $allQuizzes = $this->getAllQuizzes($user);
        $user = $user ?? Auth::user();
        $userId = $user->id;
        $now = now();

        return $allQuizzes->filter(function ($quiz) use ($userId, $now) {
            // Kiểm tra thời gian kết thúc của quiz trong course
            $userCourses = $quiz->courses->where('users', function ($users) use ($userId) {
                return $users->contains('id', $userId);
            });

            $isOverdue = false;
            foreach ($userCourses as $course) {
                $courseQuiz = $course->pivot;
                $endAt = $courseQuiz->end_at;
                
                if ($endAt && $now->gt($endAt)) {
                    $isOverdue = true;
                    break;
                }
            }

            // Nếu quiz quá hạn và user chưa hoàn thành
            if ($isOverdue) {
                $completedAttempts = $quiz->attempts->whereIn('status', [
                    QuizAttemptStatus::COMPLETED,
                    QuizAttemptStatus::GRADED
                ]);
                
                return $completedAttempts->isEmpty();
            }

            return false;
        });
    }

    /**
     * Lọc quiz đã nộp (có ít nhất 1 attempt completed/graded)
     */
    public function getSubmittedQuizzes(?User $user = null): Collection
    {
        $allQuizzes = $this->getAllQuizzes($user);
        $user = $user ?? Auth::user();
        $userId = $user->id;

        return $allQuizzes->filter(function ($quiz) use ($userId) {
            $completedAttempts = $quiz->attempts->whereIn('status', [
                QuizAttemptStatus::COMPLETED,
                QuizAttemptStatus::GRADED
            ]);
            
            return $completedAttempts->count() > 0;
        });
    }

    /**
     * Lấy thống kê quiz theo từng loại
     */
    public function getQuizStatistics(?User $user = null): array
    {
        $user = $user ?? Auth::user();
        
        $allQuizzes = $this->getAllQuizzes($user);
        $unsubmittedQuizzes = $this->getUnsubmittedQuizzes($user);
        $overdueQuizzes = $this->getOverdueQuizzes($user);
        $submittedQuizzes = $this->getSubmittedQuizzes($user);

        return [
            'total' => $allQuizzes->count(),
            'unsubmitted' => $unsubmittedQuizzes->count(),
            'overdue' => $overdueQuizzes->count(),
            'submitted' => $submittedQuizzes->count(),
        ];
    }

    /**
     * Lọc quiz theo loại
     */
    public function getQuizzesByType(string $type, ?User $user = null): Collection
    {
        return match ($type) {
            'all' => $this->getAllQuizzes($user),
            'unsubmitted' => $this->getUnsubmittedQuizzes($user),
            'overdue' => $this->getOverdueQuizzes($user),
            'submitted' => $this->getSubmittedQuizzes($user),
            default => collect(),
        };
    }

    /**
     * Kiểm tra user có thể làm quiz không
     */
    private function canTakeQuiz(Quiz $quiz, string $userId): bool
    {
        $now = now();

        // Kiểm tra thời gian trong course_quizzes
        $userCourses = $quiz->courses()->whereHas('users', function ($q) use ($userId) {
            $q->where('users.id', $userId);
        })->get();

        $canTakeInAnyCourse = false;
        foreach ($userCourses as $course) {
            $courseQuiz = $course->pivot;
            $startAt = $courseQuiz->start_at;
            $endAt = $courseQuiz->end_at;

            // Nếu không có giới hạn thời gian hoặc trong khoảng thời gian hợp lệ
            if ((!$startAt || $now->gte($startAt)) && (!$endAt || $now->lte($endAt))) {
                $canTakeInAnyCourse = true;
                break;
            }
        }

        if (!$canTakeInAnyCourse) {
            return false;
        }

        // Kiểm tra số lần thử
        return $this->hasRemainingAttempts($quiz, $userId);
    }

    /**
     * Kiểm tra còn lượt làm bài không
     */
    private function hasRemainingAttempts(Quiz $quiz, string $userId): bool
    {
        $maxAttempts = $quiz->max_attempts;
        
        // Nếu max_attempts = 0 hoặc null, cho phép làm không giới hạn
        if ($maxAttempts === 0 || $maxAttempts === null) {
            return true;
        }
        
        $usedAttempts = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('student_id', $userId)
            ->whereIn('status', [QuizAttemptStatus::COMPLETED, QuizAttemptStatus::GRADED])
            ->count();
            
        return $usedAttempts < $maxAttempts;
    }

    /**
     * Lấy trạng thái chi tiết của quiz cho user
     */
    public function getQuizDetailedStatus(Quiz $quiz, ?User $user = null): array
    {
        $user = $user ?? Auth::user();
        $userId = $user->id;
        $now = now();

        $attempts = $quiz->attempts;
        $inProgressAttempt = $attempts->where('status', QuizAttemptStatus::IN_PROGRESS)->first();
        $completedAttempts = $attempts->whereIn('status', [
            QuizAttemptStatus::COMPLETED,
            QuizAttemptStatus::GRADED
        ]);
        $latestCompletedAttempt = $completedAttempts->sortByDesc('created_at')->first();

        // Kiểm tra thời gian
        $userCourses = $quiz->courses()->whereHas('users', function ($q) use ($userId) {
            $q->where('users.id', $userId);
        })->get();

        $isOverdue = false;
        $hasValidTiming = false;
        
        foreach ($userCourses as $course) {
            $courseQuiz = $course->pivot;
            $startAt = $courseQuiz->start_at;
            $endAt = $courseQuiz->end_at;

            if ($endAt && $now->gt($endAt)) {
                $isOverdue = true;
            }
            
            if ((!$startAt || $now->gte($startAt)) && (!$endAt || $now->lte($endAt))) {
                $hasValidTiming = true;
            }
        }

        $canTake = $this->canTakeQuiz($quiz, $userId);
        $hasRemainingAttempts = $this->hasRemainingAttempts($quiz, $userId);

        return [
            'quiz_id' => $quiz->id,
            'title' => $quiz->title,
            'can_take' => $canTake,
            'is_overdue' => $isOverdue,
            'has_valid_timing' => $hasValidTiming,
            'has_remaining_attempts' => $hasRemainingAttempts,
            'in_progress_attempt' => $inProgressAttempt,
            'completed_attempts_count' => $completedAttempts->count(),
            'latest_completed_attempt' => $latestCompletedAttempt,
            'total_attempts' => $attempts->count(),
            'max_attempts' => $quiz->max_attempts,
        ];
    }

    /**
     * Lọc quiz theo khóa học
     */
    public function filterByCourse($quizzes, ?string $courseId = null)
    {
        if (!$courseId) {
            return $quizzes;
        }

        return $quizzes->filter(function ($quiz) use ($courseId) {
            return $quiz->courses->contains('id', $courseId);
        });
    }

    /**
     * Lọc quiz theo từ khóa tìm kiếm
     */
    public function filterBySearch($quizzes, ?string $searchTerm = null)
    {
        if (!$searchTerm) {
            return $quizzes;
        }

        $searchTerm = strtolower(trim($searchTerm));
        
        return $quizzes->filter(function ($quiz) use ($searchTerm) {
            return str_contains(strtolower($quiz->title), $searchTerm) ||
                   str_contains(strtolower($quiz->description ?? ''), $searchTerm) ||
                   $quiz->courses->some(function ($course) use ($searchTerm) {
                       return str_contains(strtolower($course->title), $searchTerm);
                   });
        });
    }

    /**
     * Áp dụng tất cả các bộ lọc
     */
    public function applyAllFilters($quizzes, ?string $courseId = null, ?string $searchTerm = null)
    {
        $filtered = $quizzes;
        
        // Lọc theo khóa học
        $filtered = $this->filterByCourse($filtered, $courseId);
        
        // Lọc theo từ khóa tìm kiếm
        $filtered = $this->filterBySearch($filtered, $searchTerm);
        
        return $filtered;
    }
}