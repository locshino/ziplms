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

    /**
     * Get quizzes for student (alias for getAvailableQuizzes).
     *
     * @param string $studentId
     * @return Collection
     */
    public function getQuizzesForStudent(string $studentId): Collection;

    /**
     * Check if student can take quiz (alias for canTakeQuiz).
     *
     * @param string $quizId
     * @param string $studentId
     * @return bool
     */
    public function canStudentTakeQuiz(string $quizId, string $studentId): bool;

    /**
     * Check if quiz is currently active.
     *
     * @param string $quizId
     * @return bool
     */
    public function isQuizActive(string $quizId): bool;

    /**
     * Get student attempts for a quiz.
     *
     * @param string $quizId
     * @param string $studentId
     * @return Collection
     */
    public function getStudentAttempts(string $quizId, string $studentId): Collection;

    /**
     * Get remaining attempts for student.
     *
     * @param string $quizId
     * @param string $studentId
     * @return int|null
     */
    public function getRemainingAttempts(string $quizId, string $studentId): ?int;

    /**
     * Get quiz performance statistics.
     *
     * @param string $quizId
     * @return array
     */
    public function getQuizStats(string $quizId): array;

    /**
     * Get student's best score for a quiz.
     *
     * @param string $quizId
     * @param string $studentId
     * @return float|null
     */
    public function getStudentBestScore(string $quizId, string $studentId): ?float;
}