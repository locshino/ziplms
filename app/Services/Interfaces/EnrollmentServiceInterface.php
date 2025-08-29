<?php

namespace App\Services\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface EnrollmentServiceInterface extends BaseServiceInterface
{
    /**
     * Check if user is enrolled in a course.
     */
    public function isUserEnrolledInCourse(string $userId, string $courseId): bool;

    /**
     * Get courses enrolled by user.
     */
    public function getEnrolledCoursesByUser(string $userId): Collection;

    /**
     * Enroll user in a course.
     */
    public function enrollUserInCourse(string $userId, string $courseId): array;

    /**
     * Unenroll user from a course.
     */
    public function unenrollUserFromCourse(string $userId, string $courseId): array;

    /**
     * Get enrollment count for a course.
     */
    public function getEnrollmentCountByCourse(int $courseId): int;

    /**
     * Get enrollments by course.
     */
    public function getEnrollmentsByCourse(int $courseId): Collection;

    /**
     * Get recent enrollments.
     */
    public function getRecentEnrollments(int $days = 7): Collection;
}
