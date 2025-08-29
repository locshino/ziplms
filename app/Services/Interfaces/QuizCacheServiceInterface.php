<?php

namespace App\Services\Interfaces;

use App\Models\Quiz;
use Illuminate\Database\Eloquent\Collection;

interface QuizCacheServiceInterface
{
    /**
     * Get quiz with caching
     */
    public function getQuiz(string $quizId): ?Quiz;

    /**
     * Get quiz questions with caching
     */
    public function getQuizQuestions(string $quizId);

    /**
     * Get quiz attempts with caching
     */
    public function getQuizAttempts(string $quizId): Collection;

    /**
     * Get student attempts for quiz with caching
     */
    public function getStudentAttempts(string $quizId, string $studentId): Collection;

    /**
     * Get quiz statistics with caching
     */
    public function getQuizStats(string $quizId): array;

    /**
     * Cache quiz data
     */
    public function cacheQuiz(Quiz $quiz): void;

    /**
     * Cache quiz questions
     */
    public function cacheQuizQuestions(string $quizId, $questions): void;

    /**
     * Cache quiz attempts
     */
    public function cacheQuizAttempts(string $quizId, Collection $attempts): void;

    /**
     * Cache student attempts
     */
    public function cacheStudentAttempts(string $quizId, string $studentId, Collection $attempts): void;

    /**
     * Cache quiz statistics
     */
    public function cacheQuizStats(string $quizId, array $stats): void;

    /**
     * Clear quiz cache
     */
    public function clearQuizCache(string $quizId): void;

    /**
     * Clear all quiz related cache
     */
    public function clearAllQuizCache(): void;

    /**
     * Invalidate quiz cache
     */
    public function invalidateQuizCache(string $quizId): void;

    /**
     * Get cache key for quiz
     */
    public function getQuizCacheKey(string $quizId): string;

    /**
     * Check if quiz is cached
     */
    public function isQuizCached(string $quizId): bool;
}
