<?php

namespace App\Repositories\Eloquent;

use App\Exceptions\Repositories\CourseRepositoryException;
use App\Exceptions\Repositories\RepositoryException;
use App\Models\Course;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Exception;

/**
 * Course repository implementation.
 *
 * Handles course data access operations with proper exception handling.
 *
 * @throws RepositoryException When general repository operations fail
 * @throws CourseRepositoryException When course-specific operations fail
 */
class CourseRepository extends EloquentRepository implements CourseRepositoryInterface
{
    /**
     * Get the model class name.
     *
     * @return string
     */
    protected function model(): string
    {
        return Course::class;
    }

    /**
     * Get courses by instructor.
     *
     * @param string $instructorId
     * @return Collection
     * @throws CourseRepositoryException When instructor not found or database error occurs
     */
    public function getCoursesByInstructor(string $instructorId): Collection
    {
        try {
            return $this->model->where('instructor_id', $instructorId)->get();
        } catch (Exception $e) {
            throw CourseRepositoryException::databaseError($e->getMessage());
        }
    }

    /**
     * Get published courses.
     *
     * @return Collection
     * @throws RepositoryException When database error occurs
     */
    public function getPublishedCourses(): Collection
    {
        try {
            return $this->model->where('is_published', true)->get();
        } catch (Exception $e) {
            throw RepositoryException::databaseError($e->getMessage());
        }
    }

    /**
     * Get courses with enrollments count.
     *
     * @return Collection
     * @throws RepositoryException When database error occurs
     */
    public function getCoursesWithEnrollmentsCount(): Collection
    {
        try {
            return $this->model->withCount('enrollments')->get();
        } catch (Exception $e) {
            throw RepositoryException::databaseError($e->getMessage());
        }
    }

    /**
     * Search courses by title or description.
     *
     * @param string $search
     * @return Collection
     * @throws RepositoryException When database error occurs or invalid search parameters
     */
    public function searchCourses(string $search): Collection
    {
        try {
            return $this->model->where(function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })->get();
        } catch (Exception $e) {
            throw RepositoryException::databaseError($e->getMessage());
        }
    }

    /**
     * Get courses by category.
     *
     * @param string $category
     * @return Collection
     * @throws CourseRepositoryException When invalid category provided or database error occurs
     */
    public function getCoursesByCategory(string $category): Collection
    {
        try {
            return $this->model->where('category', $category)->get();
        } catch (Exception $e) {
            throw CourseRepositoryException::databaseError($e->getMessage());
        }
    }

    /**
     * Get course with full details.
     *
     * @param string $courseId
     * @return Model|null
     * @throws CourseRepositoryException When course not found or database error occurs
     */
    public function getCourseWithDetails(string $courseId): ?Model
    {
        try {
            return $this->model->with(['instructor', 'enrollments', 'assignments', 'quizzes'])
                ->find($courseId);
        } catch (Exception $e) {
            throw CourseRepositoryException::databaseError($e->getMessage());
        }
    }
}
