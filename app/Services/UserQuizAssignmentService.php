<?php

namespace App\Services;

use App\Models\Quiz;
use App\Models\User;
use App\Repositories\Interfaces\EnrollmentRepositoryInterface;
use App\Repositories\Interfaces\QuizRepositoryInterface;
use App\Services\Interfaces\UserQuizAssignmentServiceInterface;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class UserQuizAssignmentService implements UserQuizAssignmentServiceInterface
{
    protected QuizRepositoryInterface $quizRepository;

    protected EnrollmentRepositoryInterface $enrollmentRepository;

    public function __construct(
        QuizRepositoryInterface $quizRepository,
        EnrollmentRepositoryInterface $enrollmentRepository
    ) {
        $this->quizRepository = $quizRepository;
        $this->enrollmentRepository = $enrollmentRepository;
    }

    /**
     * Lấy tất cả quiz được giao cho người dùng
     */
    public function getAssignedQuizzes(User $user): Collection
    {
        $cacheKey = "user_assigned_quizzes_{$user->id}";

        return Cache::remember($cacheKey, 300, function () use ($user) {
            // Lấy các khóa học mà user đã đăng ký
            $enrolledCourses = $this->getEnrolledCourses($user);

            if ($enrolledCourses->isEmpty()) {
                return collect();
            }

            // Lấy quiz từ các khóa học đã đăng ký
            $quizzes = $this->quizRepository->getQuizzesByCourseIds(
                $enrolledCourses->pluck('id')->toArray()
            );

            // Lọc quiz theo điều kiện truy cập
            return $quizzes->filter(function ($quiz) use ($user) {
                return $this->canUserAccessQuiz($user, $quiz);
            });
        });
    }

    /**
     * Lấy quiz được giao theo khóa học cụ thể
     */
    public function getAssignedQuizzesByCourse(User $user, int $courseId): Collection
    {
        // Kiểm tra user có đăng ký khóa học không
        if (! $this->isUserEnrolledInCourse($user, $courseId)) {
            return collect();
        }

        $quizzes = $this->quizRepository->getQuizzesByCourseId($courseId);

        return $quizzes->filter(function ($quiz) use ($user) {
            return $this->canUserAccessQuiz($user, $quiz);
        });
    }

    /**
     * Kiểm tra user có thể truy cập quiz không
     */
    public function canUserAccessQuiz(User $user, Quiz $quiz): bool
    {
        // Kiểm tra user có đăng ký khóa học chứa quiz không
        if (! $this->isUserEnrolledInCourse($user, $quiz->course_id)) {
            return false;
        }

        // Kiểm tra trạng thái quiz
        if (! $quiz->is_active) {
            return false;
        }

        // Kiểm tra thời gian mở quiz
        $now = Carbon::now();

        if ($quiz->start_time && $now->lt($quiz->start_time)) {
            return false;
        }

        if ($quiz->end_time && $now->gt($quiz->end_time)) {
            return false;
        }

        // Kiểm tra điều kiện tiên quyết (nếu có)
        if ($quiz->prerequisite_quiz_id) {
            if (! $this->hasCompletedPrerequisite($user, $quiz->prerequisite_quiz_id)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Lấy thống kê quiz được giao cho user
     */
    public function getQuizAssignmentStats(User $user): array
    {
        $assignedQuizzes = $this->getAssignedQuizzes($user);

        $stats = [
            'total_assigned' => $assignedQuizzes->count(),
            'available' => 0,
            'in_progress' => 0,
            'completed' => 0,
            'overdue' => 0,
            'upcoming' => 0,
        ];

        $now = Carbon::now();

        foreach ($assignedQuizzes as $quiz) {
            $status = $this->getQuizStatusForUser($user, $quiz);

            switch ($status) {
                case 'available':
                    $stats['available']++;
                    break;
                case 'in_progress':
                    $stats['in_progress']++;
                    break;
                case 'completed':
                    $stats['completed']++;
                    break;
                case 'overdue':
                    $stats['overdue']++;
                    break;
                case 'upcoming':
                    $stats['upcoming']++;
                    break;
            }
        }

        return $stats;
    }

    /**
     * Lấy trạng thái quiz cho user cụ thể
     */
    public function getQuizStatusForUser(User $user, Quiz $quiz): string
    {
        $now = Carbon::now();

        // Kiểm tra thời gian
        if ($quiz->start_time && $now->lt($quiz->start_time)) {
            return 'upcoming';
        }

        if ($quiz->end_time && $now->gt($quiz->end_time)) {
            return 'overdue';
        }

        // Kiểm tra trạng thái làm bài
        $latestAttempt = $quiz->attempts()
            ->where('user_id', $user->id)
            ->latest()
            ->first();

        if ($latestAttempt) {
            if (in_array($latestAttempt->status, ['completed', 'submitted'])) {
                return 'completed';
            } elseif ($latestAttempt->status === 'in_progress') {
                return 'in_progress';
            }
        }

        return 'available';
    }

    /**
     * Lấy các khóa học mà user đã đăng ký
     */
    protected function getEnrolledCourses(User $user): Collection
    {
        return $this->enrollmentRepository->getEnrolledCoursesByUser($user->id);
    }

    /**
     * Kiểm tra user có đăng ký khóa học không
     */
    protected function isUserEnrolledInCourse(User $user, int $courseId): bool
    {
        return $this->enrollmentRepository->isUserEnrolledInCourse($user->id, $courseId);
    }

    /**
     * Kiểm tra user đã hoàn thành quiz tiên quyết chưa
     */
    protected function hasCompletedPrerequisite(User $user, int $prerequisiteQuizId): bool
    {
        $prerequisiteQuiz = $this->quizRepository->find($prerequisiteQuizId);

        if (! $prerequisiteQuiz) {
            return false;
        }

        $completedAttempt = $prerequisiteQuiz->attempts()
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->first();

        return $completedAttempt !== null;
    }

    /**
     * Xóa cache quiz assignment cho user
     */
    public function clearUserQuizCache(User $user): void
    {
        Cache::forget("user_assigned_quizzes_{$user->id}");
    }

    /**
     * Lấy quiz sắp đến hạn cho user
     */
    public function getUpcomingQuizzes(User $user, int $days = 7): Collection
    {
        $assignedQuizzes = $this->getAssignedQuizzes($user);
        $now = Carbon::now();
        $futureDate = $now->copy()->addDays($days);

        return $assignedQuizzes->filter(function ($quiz) use ($now, $futureDate) {
            return $quiz->end_time &&
                   $quiz->end_time->gt($now) &&
                   $quiz->end_time->lte($futureDate);
        });
    }

    /**
     * Lấy quiz quá hạn cho user
     */
    public function getOverdueQuizzes(User $user): Collection
    {
        $assignedQuizzes = $this->getAssignedQuizzes($user);
        $now = Carbon::now();

        return $assignedQuizzes->filter(function ($quiz) use ($user, $now) {
            // Quiz quá hạn và chưa hoàn thành
            if (! $quiz->end_time || $quiz->end_time->gte($now)) {
                return false;
            }

            $completedAttempt = $quiz->attempts()
                ->where('user_id', $user->id)
                ->where('status', 'completed')
                ->first();

            return ! $completedAttempt;
        });
    }
}
