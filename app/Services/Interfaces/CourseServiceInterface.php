<?php

namespace App\Services\Interfaces;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CourseServiceInterface extends BaseServiceInterface
{
    /**
     * Get courses with pagination and filters.
     */
    public function getPaginatedCourses(
        int $perPage = 15,
        ?string $search = null,
        ?string $teacherId = null,
        ?string $startDateFrom = null,
        ?string $startDateTo = null,
        ?string $status = null,
        ?array $orderBy = null
    ): LengthAwarePaginator;

    /**
     * Get courses by teacher.
     */
    public function getCoursesByTeacher(string $teacherId): Collection;

    /**
     * Get published courses.
     */
    public function getPublishedCourses(): Collection;

    /**
     * Search courses by name or description.
     */
    public function searchCourses(string $search): Collection;

    /**
     * Get courses enrolled by user.
     */
    public function getCoursesEnrolledByUser(User $user): Collection;

    /**
     * Get all available courses (not ended).
     */
    public function getAvailableCourses(): Collection;

    /**
     * Get completed courses (ended and user not enrolled).
     */
    public function getCompletedCourses(User $user): Collection;

    /**
     * Check if user can access course.
     */
    public function canUserAccessCourse(User $user, Course $course): bool;

    /**
     * Get teachers for filter dropdown.
     */
    public function getTeachersForFilter(): Collection;

    /**
     * Get course statuses for filter dropdown.
     */
    public function getCourseStatusesForFilter(): array;

    /**
     * Check if user can create courses.
     */
    public function canUserCreateCourses(User $user): bool;

    /**
     * Check if user can edit course.
     */
    public function canUserEditCourse(User $user, Course $course): bool;

    /**
     * Check if user can delete course.
     */
    public function canUserDeleteCourse(User $user, Course $course): bool;

    /**
     * Check if user can restore course.
     */
    public function canUserRestoreCourse(User $user, Course $course): bool;

    /**
     * Get course with details for display.
     */
    public function getCourseWithDetails(int $courseId): ?Course;

    /**
     * Get course image URL or gradient.
     */
    public function getCourseImageUrl(Course $course): string;

    /**
     * Get courses that user is enrolled in with pagination.
     */
    public function getUserEnrolledCourses(string $userId, int $perPage = 8, int $page = 1): array;

    /**
     * Get completed courses for user with pagination.
     */
    public function getUserCompletedCourses(string $userId, int $perPage = 8, int $page = 1): array;

    /**
     * Get all published courses with pagination.
     */
    public function getAllPublishedCourses(int $perPage = 8, int $page = 1, ?string $search = null): array;

    /**
     * Check if user can view a specific course.
     */
    public function canUserViewCourse(User $user, Course $course): bool;

    /**
     * Get course progress for a user.
     */
    public function getCourseProgress(Course $course, User $user): int;

    /**
     * Get the current status of a course.
     */
    public function getCurrentStatus(string $courseId): ?string;

    /**
     * Get the current status enum of a course.
     */
    public function getCurrentStatusEnum(string $courseId): ?\App\Enums\CourseStatusEnum;

    /**
     * Check if course is published.
     */
    public function isPublished(string $courseId): bool;

    /**
     * Check if course is draft.
     */
    public function isDraft(string $courseId): bool;

    /**
     * Check if course is archived.
     */
    public function isArchived(string $courseId): bool;

    /**
     * Check if course is suspended.
     */
    public function isSuspended(string $courseId): bool;

    /**
     * Get courses by current status.
     */
    public function getCoursesByCurrentStatus(string $status): Collection;

    /**
     * Get courses excluding specific status.
     */
    public function getCoursesExcludingStatus(string $status): Collection;

    /**
     * Check if course has ever had a specific status.
     */
    public function hasEverHadStatus(string $courseId, string $status): bool;

    /**
     * Check if course has never had a specific status.
     */
    public function hasNeverHadStatus(string $courseId, string $status): bool;
}