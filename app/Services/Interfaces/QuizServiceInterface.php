<?php

namespace App\Services\Interfaces;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface QuizServiceInterface extends BaseServiceInterface
{
    /**
     * Get available quizzes for student.
     *
     * @param string $studentId
     * @return Collection
     */
    public function getAvailableQuizzes(string $studentId): Collection;

    /**
     * Start a new quiz attempt.
     *
     * @param string $quizId
     * @param string $studentId
     * @return QuizAttempt
     */
    public function startQuizAttempt(string $quizId, string $studentId): QuizAttempt;

    /**
     * Continue an existing quiz attempt.
     *
     * @param string $quizId
     * @param string $studentId
     * @return QuizAttempt|null
     */
    public function continueQuizAttempt(string $quizId, string $studentId): ?QuizAttempt;

    /**
     * Submit quiz attempt.
     *
     * @param string $attemptId
     * @param array $answers
     * @return QuizAttempt
     */
    public function submitQuizAttempt(string $attemptId, array $answers): QuizAttempt;

    /**
     * Get quiz attempt history for student.
     *
     * @param string $quizId
     * @param string $studentId
     * @return Collection
     */
    public function getAttemptHistory(string $quizId, string $studentId): Collection;

    /**
     * Get quiz attempt with answers.
     *
     * @param string $attemptId
     * @return QuizAttempt|null
     */
    public function getAttemptWithAnswers(string $attemptId): ?QuizAttempt;

    /**
     * Check if student can take quiz.
     *
     * @param string $quizId
     * @param string $studentId
     * @return bool
     */
    public function canTakeQuiz(string $quizId, string $studentId): bool;

    /**
     * Calculate quiz score.
     *
     * @param QuizAttempt $attempt
     * @return float
     */
    public function calculateScore(QuizAttempt $attempt): float;
}