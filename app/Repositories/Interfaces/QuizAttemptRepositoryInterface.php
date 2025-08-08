<?php

namespace App\Repositories\Interfaces;

use App\Models\QuizAttempt;
use Illuminate\Database\Eloquent\Collection;

interface QuizAttemptRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * Get student's attempts for a quiz.
     *
     * @param string $quizId
     * @param string $studentId
     * @return Collection
     */
    public function getStudentAttempts(string $quizId, string $studentId): Collection;

    /**
     * Get student's latest attempt for a quiz.
     *
     * @param string $quizId
     * @param string $studentId
     * @return QuizAttempt|null
     */
    public function getLatestAttempt(string $quizId, string $studentId): ?QuizAttempt;

    /**
     * Get attempt with answers.
     *
     * @param string $id
     * @return QuizAttempt|null
     */
    public function getWithAnswers(string $id): ?QuizAttempt;

    /**
     * Count student attempts for a quiz.
     *
     * @param string $quizId
     * @param string $studentId
     * @return int
     */
    public function countStudentAttempts(string $quizId, string $studentId): int;

    /**
     * Get incomplete attempt for student.
     *
     * @param string $quizId
     * @param string $studentId
     * @return QuizAttempt|null
     */
    public function getIncompleteAttempt(string $quizId, string $studentId): ?QuizAttempt;

    /**
     * Get completed attempts for a quiz.
     *
     * @param string $quizId
     * @return Collection
     */
    public function getCompletedAttemptsByQuiz(string $quizId): Collection;
}