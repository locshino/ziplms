<?php

namespace App\Repositories\Interfaces;

use App\Models\Quiz;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface QuizRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * Get quizzes by course ID.
     */
    public function getByCourseId(string $courseId): Collection;

    /**
     * Get paginated quizzes with course information.
     */
    public function getPaginatedWithCourse(int $perPage = 15): LengthAwarePaginator;

    /**
     * Get quiz with questions and answer choices.
     */
    public function getWithQuestionsAndChoices(string $id): ?Quiz;

    /**
     * Get available quizzes for student.
     */
    public function getAvailableForStudent(string $studentId): Collection;

    /**
     * Get quizzes by multiple course IDs.
     */
    public function getQuizzesByCourseIds(array $courseIds): Collection;

    /**
     * Get quizzes by course ID.
     */
    public function getQuizzesByCourseId(int $courseId): Collection;

    /**
     * Get quizzes for student.
     */
    public function getQuizzesByStudent(string $studentId): Collection;

    /**
     * Get quizzes by course.
     */
    public function getQuizzesByCourse(string $courseId): Collection;

    /**
     * Get active quizzes.
     */
    public function getActiveQuizzes(): Collection;

    /**
     * Get quiz with questions.
     */
    public function getQuizWithQuestions(string $quizId): ?Quiz;

    /**
     * Get quiz with attempts.
     */
    public function getQuizWithAttempts(string $quizId): ?Quiz;

    /**
     * Get upcoming quizzes.
     */
    public function getUpcomingQuizzes(): Collection;

    /**
     * Get paginated quizzes with course.
     */
    public function getPaginatedQuizzesWithCourse(int $perPage = 15): \Illuminate\Pagination\LengthAwarePaginator;

    /**
     * Get question with answer choices.
     */
    public function getQuestionWithAnswerChoices(string $questionId): ?\App\Models\Question;

    /**
     * Get questions with answer choices by quiz ID.
     */
    public function getQuestionsWithAnswerChoicesByQuizId(string $quizId): Collection;
}
