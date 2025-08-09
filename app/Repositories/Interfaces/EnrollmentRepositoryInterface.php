<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface EnrollmentRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * Get enrollments by course.
     *
     * @param string $courseId
     * @return Collection
     */
    public function getEnrollmentsByCourse(string $courseId): Collection;

    /**
     * Get enrollments by student.
     *
     * @param string $studentId
     * @return Collection
     */
    public function getEnrollmentsByStudent(string $studentId): Collection;

    /**
     * Check if student is enrolled in course.
     *
     * @param string $studentId
     * @param string $courseId
     * @return bool
     */
    public function isStudentEnrolled(string $studentId, string $courseId): bool;

    /**
     * Get recent enrollments.
     *
     * @param int $days
     * @return Collection
     */
    public function getRecentEnrollments(int $days = 7): Collection;

    /**
     * Get enrollment count by course.
     *
     * @param string $courseId
     * @return int
     */
    public function getEnrollmentCountByCourse(string $courseId): int;

    /**
     * Get enrollment with course and student details.
     *
     * @param string $enrollmentId
     * @return Model|null
     */
    public function getEnrollmentWithDetails(string $enrollmentId): ?Model;

    /**
     * Enroll student in course.
     *
     * @param string $studentId
     * @param string $courseId
     * @return Model
     */
    public function enrollStudent(string $studentId, string $courseId): Model;
}