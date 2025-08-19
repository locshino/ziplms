<?php

namespace App\Services\Interfaces;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Interface for Course Management Service.
 * 
 * This service coordinates between multiple repositories to provide
 * comprehensive course management functionality including enrollment,
 * content management, and progress tracking.
 */
interface CourseManagementServiceInterface
{
    /**
     * Create a new course with instructor assignment.
     *
     * @param array $courseData Course creation data
     * @param string $instructorId Instructor user ID
     * @return Course
     * @throws \App\Exceptions\Services\CourseManagementServiceException
     */
    public function createCourseWithInstructor(array $courseData, string $instructorId): Course;

    /**
     * Enroll a student in a course.
     *
     * @param string $studentId Student user ID
     * @param string $courseId Course ID
     * @return bool
     * @throws \App\Exceptions\Services\CourseManagementServiceException
     */
    public function enrollStudent(string $studentId, string $courseId): bool;

    /**
     * Get comprehensive course dashboard data for an instructor.
     *
     * @param string $courseId Course ID
     * @param string $instructorId Instructor user ID
     * @return array
     * @throws \App\Exceptions\Services\CourseManagementServiceException
     */
    public function getCourseDashboard(string $courseId, string $instructorId): array;

    /**
     * Get student progress in a course.
     *
     * @param string $studentId Student user ID
     * @param string $courseId Course ID
     * @return array
     * @throws \App\Exceptions\Services\CourseManagementServiceException
     */
    public function getStudentProgress(string $studentId, string $courseId): array;

    /**
     * Get courses with enrollment statistics.
     *
     * @param array $filters Optional filters
     * @param int $perPage Items per page
     * @return LengthAwarePaginator
     * @throws \App\Exceptions\Services\CourseManagementServiceException
     */
    public function getCoursesWithStats(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    /**
     * Archive a course and handle related data.
     *
     * @param string $courseId Course ID
     * @param string $adminId Admin user ID
     * @return bool
     * @throws \App\Exceptions\Services\CourseManagementServiceException
     */
    public function archiveCourse(string $courseId, string $adminId): bool;

    /**
     * Get course completion report.
     *
     * @param string $courseId Course ID
     * @return array
     * @throws \App\Exceptions\Services\CourseManagementServiceException
     */
    public function getCourseCompletionReport(string $courseId): array;

    /**
     * Bulk enroll students in a course.
     *
     * @param array $studentIds Array of student user IDs
     * @param string $courseId Course ID
     * @return array Results with success/failure counts
     * @throws \App\Exceptions\Services\CourseManagementServiceException
     */
    public function bulkEnrollStudents(array $studentIds, string $courseId): array;
}