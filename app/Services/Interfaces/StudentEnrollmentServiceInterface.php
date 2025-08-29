<?php

namespace App\Services\Interfaces;

use App\Models\Course;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Student Enrollment Service Interface
 */
interface StudentEnrollmentServiceInterface
{
    /**
     * Get enrolled students for a course
     */
    public function getEnrolledStudents(Course $course): Collection;

    /**
     * Bulk enroll students in a course
     */
    public function bulkEnrollStudents(
        Course $course,
        array $studentIds,
        ?Carbon $enrollmentDate = null,
        ?string $notes = null
    ): array;

    /**
     * Unenroll a student from a course
     */
    public function unenrollStudent(Course $course, int $studentId): array;

    /**
     * Get available students for enrollment
     */
    public function getAvailableStudents(Course $course): Collection;
}
