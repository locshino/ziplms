<?php

namespace App\Services\Interfaces;

use App\Models\Course;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Course Display Service Interface
 */
interface CourseDisplayServiceInterface
{
    /**
     * Get courses for "My Courses" tab (enrolled courses)
     */
    public function getMyCourses(User $user, int $perPage = 12): LengthAwarePaginator;

    /**
     * Get courses for "All Courses" tab (published courses)
     */
    public function getAllCourses(int $perPage = 12): LengthAwarePaginator;

    /**
     * Get courses for "Completed" tab (completed courses)
     */
    public function getCompletedCourses(User $user, int $perPage = 12): LengthAwarePaginator;

    /**
     * Get course progress for a user
     */
    public function getCourseProgress(Course $course, User $user): int;

    /**
     * Enroll user in a course
     */
    public function enrollUserInCourse(int $userId, int $courseId): array;

    /**
     * Unenroll user from a course
     */
    public function unenrollUserFromCourse(int $userId, int $courseId): array;

    /**
     * Check if user can view a course
     */
    public function canUserViewCourse(Course $course, User $user): bool;

    /**
     * Generate course image placeholder based on course ID
     */
    public function generateCourseImagePlaceholder(string $courseId): string;

    /**
     * Get courses by tab type
     */
    public function getCoursesByTab(string $tab, User $user, int $perPage = 12): LengthAwarePaginator;

    /**
     * Check if user is enrolled in a course
     */
    public function isUserEnrolledInCourse(int $userId, int $courseId): bool;
}
