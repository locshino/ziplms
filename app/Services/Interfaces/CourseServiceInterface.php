<?php

namespace App\Services\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface CourseServiceInterface extends BaseServiceInterface
{
    /**
     * Create a new course.
     *
     * @param array $payload
     * @return Model
     */
    public function createCourse(array $payload): Model;

    /**
     * Get courses by instructor.
     *
     * @param string $instructorId
     * @return Collection
     */
    public function getCoursesByInstructor(string $instructorId): Collection;

    /**
     * Get all published courses.
     *
     * @return Collection
     */
    public function getPublishedCourses(): Collection;

    /**
     * Get courses with enrollment statistics.
     *
     * @return Collection
     */
    public function getCoursesWithStats(): Collection;

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
     * Get course with full details including assignments and quizzes.
     *
     * @param string $courseId
     * @return Model|null
     */
    public function getCourseWithDetails(string $courseId): ?Model;

    /**
     * Publish or unpublish a course.
     *
     * @param string $courseId
     * @param bool $isPublished
     * @return bool
     */
    public function togglePublishStatus(string $courseId, bool $isPublished): bool;

    /**
     * Enroll a student in a course.
     *
     * @param string $courseId
     * @param string $studentId
     * @return bool
     */
    public function enrollStudent(string $courseId, string $studentId): bool;

    /**
     * Get enrollment count for a course.
     *
     * @param string $courseId
     * @return int
     */
    public function getEnrollmentCount(string $courseId): int;

    /**
     * Get enrolled students for a course.
     *
     * @param string $courseId
     * @return Collection
     */
    public function getEnrolledStudents(string $courseId): Collection;

    /**
     * Check if a student can access a course.
     *
     * @param string $courseId
     * @param string $studentId
     * @return bool
     */
    public function canStudentAccessCourse(string $courseId, string $studentId): bool;

    /**
     * Update course information.
     *
     * @param string $courseId
     * @param array $payload
     * @return bool
     */
    public function updateCourse(string $courseId, array $payload): bool;

    /**
     * Delete a course (soft delete).
     *
     * @param string $courseId
     * @return bool
     */
    public function deleteCourse(string $courseId): bool;
}