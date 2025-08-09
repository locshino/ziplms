<?php

namespace App\Services\Interfaces;

use App\Models\Quiz;
use App\Models\User;
use Illuminate\Support\Collection;

interface UserQuizAssignmentServiceInterface
{
    /**
     * Lấy tất cả quiz được giao cho người dùng
     */
    public function getAssignedQuizzes(User $user): Collection;

    /**
     * Lấy quiz được giao theo khóa học cụ thể
     */
    public function getAssignedQuizzesByCourse(User $user, int $courseId): Collection;

    /**
     * Kiểm tra user có thể truy cập quiz không
     */
    public function canUserAccessQuiz(User $user, Quiz $quiz): bool;

    /**
     * Lấy thống kê quiz được giao cho user
     */
    public function getQuizAssignmentStats(User $user): array;

    /**
     * Lấy trạng thái quiz cho user cụ thể
     */
    public function getQuizStatusForUser(User $user, Quiz $quiz): string;

    /**
     * Xóa cache quiz assignment cho user
     */
    public function clearUserQuizCache(User $user): void;

    /**
     * Lấy quiz sắp đến hạn cho user
     */
    public function getUpcomingQuizzes(User $user, int $days = 7): Collection;

    /**
     * Lấy quiz quá hạn cho user
     */
    public function getOverdueQuizzes(User $user): Collection;
}
