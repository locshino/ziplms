<?php

namespace App\Repositories\Interfaces;

use App\Models\QuizAttempt;
use Illuminate\Database\Eloquent\Collection;

interface QuizAttemptRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * Get student's attempts for a quiz.
     */
    public function getStudentAttempts(string $quizId, string $studentId): Collection;

    /**
     * Get student's latest attempt for a quiz.
     */
    public function getLatestAttempt(string $quizId, string $studentId): ?QuizAttempt;

    /**
     * Get attempt with answers.
     */
    public function getWithAnswers(string $id): ?QuizAttempt;

    /**
     * Count student attempts for a quiz.
     */
    public function countStudentAttempts(string $quizId, string $studentId): int;

    /**
     * Get incomplete attempt for student.
     */
    public function getIncompleteAttempt(string $quizId, string $studentId): ?QuizAttempt;

    /**
     * Get completed attempts for a quiz.
     */
    public function getCompletedAttemptsByQuiz(string $quizId): Collection;

    /**
     * Get all attempts for a quiz.
     */
    public function getByQuizId(string $quizId): Collection;

    /**
     * Get attempts by quiz ID (alias for getByQuizId).
     */
    public function getAttemptsByQuizId(string $quizId): Collection;
}
