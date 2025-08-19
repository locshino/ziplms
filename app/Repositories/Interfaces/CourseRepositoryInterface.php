<?php

namespace App\Repositories\Interfaces;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CourseRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * Get course status label.
     */
    public function getCourseStatusLabel(Course $course): string;

    /**
     * Get course status color.
     */
    public function getCourseStatusColor(Course $course): string;

    /**
     * Get available parent courses.
     */
    public function getAvailableParents(?int $excludeId = null): Collection;

    /**
     * Apply date filter to query.
     */
    public function applyDateFilter(Builder $query, ?string $from, ?string $to): Builder;

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
     * Get courses with enrollments count.
     */
    public function getCoursesWithEnrollmentsCount(): Collection;

    /**
     * Search courses by name or description.
     */
    public function searchCourses(string $search): Collection;

    /**
     * Get courses by category.
     */
    public function getCoursesByCategory(string $category): Collection;

    /**
     * Get course with details (enrollments, assignments, quizzes).
     */
    public function getCourseWithDetails(string $courseId): ?Course;

    /**
     * Get all courses with filters for CoursesPage.
     */
    public function getAllCoursesForPage(
        int $perPage = 8,
        int $page = 1,
        ?string $search = null,
        ?string $teacherId = null,
        ?string $startDateFrom = null,
        ?string $startDateTo = null,
        ?string $status = null
    ): array;

    /**
     * Get courses by user role and permissions.
     */
    public function getCoursesByUserPermissions(User $user): Collection;

    /**
     * Get active courses (not ended).
     */
    public function getActiveCourses(): Collection;

    /**
     * Get completed courses.
     */
    public function getCompletedCourses(): Collection;

    /**
     * Get courses enrolled by user.
     */
    public function getCoursesEnrolledByUser(string $userId): Collection;

    /**
     * Check if user can access course.
     */
    public function canUserAccessCourse(User $user, string $courseId): bool;

    /**
     * Get teachers list for filter.
     */
    public function getTeachersForFilter(): Collection;

    /**
     * Get course statuses for filter.
     */
    public function getCourseStatusesForFilter(): array;

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