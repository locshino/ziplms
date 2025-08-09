<?php

namespace App\Services\Interfaces;

use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface QuizServiceInterface extends BaseServiceInterface
{
    /**
     * Get available quizzes for student.
     */
    public function getAvailableQuizzes(string $studentId): Collection;

    /**
     * Start a new quiz attempt.
     */
    public function startQuizAttempt(string $quizId, string $studentId): QuizAttempt;

    /**
     * Continue an existing quiz attempt.
     */
    public function continueQuizAttempt(string $quizId, string $studentId): ?QuizAttempt;

    /**
     * Submit quiz attempt.
     */
    public function submitQuizAttempt(string $attemptId, array $answers): QuizAttempt;

    /**
     * Get quiz attempt history for student.
     */
    public function getAttemptHistory(string $quizId, string $studentId): Collection;

    /**
     * Get quiz attempt with answers.
     */
    public function getAttemptWithAnswers(string $attemptId): ?QuizAttempt;

    /**
     * Check if student can take quiz.
     */
    public function canTakeQuiz(string $quizId, string $studentId): bool;

    /**
     * Calculate quiz score.
     */
    public function calculateScore(QuizAttempt $attempt): float;

    /**
     * Get quizzes for student (alias for getAvailableQuizzes).
     */
    public function getQuizzesForStudent(string $studentId): Collection;

    /**
     * Check if student can take quiz (alias for canTakeQuiz).
     */
    public function canStudentTakeQuiz(string $quizId, string $studentId): bool;

    /**
     * Check if quiz is currently active.
     */
    public function isQuizActive(string $quizId): bool;

    public function saveQuizAnswers(QuizAttempt $quizAttempt, array $answers): QuizAttempt;

    /**
     * Get student attempts for a quiz.
     */
    public function getStudentAttempts(string $quizId, string $studentId): Collection;

    /**
     * Get student's latest attempt for a quiz.
     */
    public function getLatestAttempt(string $quizId, string $studentId): ?QuizAttempt;

    /**
     * Get completed attempts for a quiz.
     */
    public function getCompletedAttemptsByQuiz(string $quizId): Collection;

    /**
     * Get remaining attempts for student.
     */
    public function getRemainingAttempts(string $quizId, string $studentId): ?int;

    /**
     * Get quiz performance statistics.
     */
    public function getQuizStats(string $quizId): array;

    /**
     * Get student's best score for a quiz.
     */
    public function getStudentBestScore(string $quizId, string $studentId): ?float;

    /**
     * Get quizzes by course ID.
     */
    public function getQuizzesByCourseId(string $courseId): Collection;

    /**
     * Get paginated quizzes with course information.
     */
    public function getPaginatedQuizzesWithCourse(int $perPage = 15): LengthAwarePaginator;

    /**
     * Get available quizzes for student (alias for getAvailableQuizzes).
     */
    public function getAvailableQuizzesForStudent(string $studentId): Collection;

    /**
     * Get all quiz attempts for a quiz.
     */
    public function getQuizAttempts(string $quizId): Collection;

    /**
     * Get quiz with questions and answer choices.
     */
    public function getQuizWithQuestionsAndChoices(string $quizId): ?Quiz;

    /**
     * Get questions by quiz ID.
     */
    public function getQuestionsByQuizId(string $quizId): Collection;

    /**
     * Get question with answer choices.
     */
    public function getQuestionWithAnswerChoices(string $questionId): ?Question;

    /**
     * Get questions with answer choices by quiz ID.
     */
    public function getQuestionsWithAnswerChoicesByQuizId(string $quizId): Collection;
}
