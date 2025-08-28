<?php

namespace App\Services\Interfaces;

use App\Models\Quiz;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface QuizFilterServiceInterface
{
    /**
     * Lấy tất cả quiz của user với thông tin chi tiết
     */
    public function getAllQuizzes(?User $user = null): Collection;

    /**
     * Lọc quiz chưa nộp (chưa có attempt hoặc có attempt in_progress)
     */
    public function getUnsubmittedQuizzes(?User $user = null): Collection;

    /**
     * Lọc quiz đã nộp (có attempt completed)
     */
    public function getSubmittedQuizzes(?User $user = null): Collection;

    /**
     * Lọc quiz đang làm (có attempt in_progress)
     */
    public function getInProgressQuizzes(?User $user = null): Collection;

    /**
     * Lọc quiz theo trạng thái
     */
    public function getQuizzesByStatus(string $status, ?User $user = null): Collection;

    /**
     * Lọc quiz theo khóa học
     */
    public function getQuizzesByCourse(string $courseId, ?User $user = null): Collection;

    /**
     * Lọc quiz theo thời gian
     */
    public function getQuizzesByDateRange(\Carbon\Carbon $startDate, \Carbon\Carbon $endDate, ?User $user = null): Collection;

    /**
     * Lọc quiz sắp hết hạn
     */
    public function getUpcomingDeadlineQuizzes(?User $user = null, int $days = 7): Collection;

    /**
     * Lọc quiz đã quá hạn
     */
    public function getOverdueQuizzes(?User $user = null): Collection;

    /**
     * Lọc quiz theo điểm số
     */
    public function getQuizzesByScoreRange(float $minScore, float $maxScore, ?User $user = null): Collection;

    /**
     * Lọc quiz theo số lần thử
     */
    public function getQuizzesByAttemptCount(int $attemptCount, ?User $user = null): Collection;

    /**
     * Tìm kiếm quiz theo từ khóa
     */
    public function searchQuizzes(string $keyword, ?User $user = null): Collection;

    /**
     * Lọc quiz theo độ khó
     */
    public function getQuizzesByDifficulty(string $difficulty, ?User $user = null): Collection;

    /**
     * Lọc quiz theo thời gian làm bài
     */
    public function getQuizzesByDuration(int $minDuration, int $maxDuration, ?User $user = null): Collection;

    /**
     * Áp dụng tất cả các bộ lọc (khóa học và tìm kiếm)
     */
    public function applyAllFilters($quizzes, ?string $courseId = null, ?string $searchTerm = null);

    /**
     * Lấy thống kê quiz theo từng loại
     */
    public function getQuizStatistics(?User $user = null): array;

    /**
     * Lọc quiz theo loại
     */
    public function getQuizzesByType(string $type, ?User $user = null): Collection;

    /**
     * Lấy trạng thái chi tiết của quiz cho user
     */
    public function getQuizDetailedStatus(Quiz $quiz, ?User $user = null): array;

    /**
     * Lọc quiz theo khóa học
     */
    public function filterByCourse($quizzes, ?string $courseId = null);

    /**
     * Lọc quiz theo từ khóa tìm kiếm
     */
    public function filterBySearch($quizzes, ?string $searchTerm = null);
}