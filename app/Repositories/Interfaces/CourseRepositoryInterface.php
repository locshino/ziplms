<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface CourseRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * Get courses by instructor.
     *
     * @param string $instructorId
     * @return Collection
     */
    public function getCoursesByInstructor(string $instructorId): Collection;

    /**
     * Get published courses.
     *
     * @return Collection
     */
    public function getPublishedCourses(): Collection;

    /**
     * Get courses with enrollments count.
     *
     * @return Collection
     */
    public function getCoursesWithEnrollmentsCount(): Collection;

    /**
     * Search courses by title or description.
     *
     * @param string $search
     * @return Collection
     */
    public function searchCourses(string $search): Collection;

    /**
     * Get courses by category.
     *
     * @param string $category
     * @return Collection
     */
    public function getCoursesByCategory(string $category): Collection;

    /**
     * Get course with full details.
     *
     * @param string $courseId
     * @return Model|null
     */
    public function getCourseWithDetails(string $courseId): ?Model;
}