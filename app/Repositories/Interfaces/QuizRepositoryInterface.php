<?php

namespace App\Repositories\Interfaces;

use App\Models\Quiz;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface QuizRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * Get quizzes by course ID.
     *
     * @param string $courseId
     * @return Collection
     */
    public function getByCourseId(string $courseId): Collection;

    /**
     * Get paginated quizzes with course information.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPaginatedWithCourse(int $perPage = 15): LengthAwarePaginator;

    /**
     * Get quiz with questions and answer choices.
     *
     * @param string $id
     * @return Quiz|null
     */
    public function getWithQuestionsAndChoices(string $id): ?Quiz;

    /**
     * Get available quizzes for student.
     *
     * @param string $studentId
     * @return Collection
     */
    public function getAvailableForStudent(string $studentId): Collection;
}