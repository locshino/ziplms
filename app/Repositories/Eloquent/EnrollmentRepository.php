<?php

namespace App\Repositories\Eloquent;

use App\Exceptions\Repositories\RepositoryException;
use App\Models\Enrollment;
use App\Repositories\Interfaces\EnrollmentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Exception;

/**
 * Enrollment repository implementation.
 *
 * Handles enrollment data access operations with proper exception handling.
 *
 * @throws RepositoryException When repository operations fail
 */
class EnrollmentRepository extends EloquentRepository implements EnrollmentRepositoryInterface
{
    /**
     * Get the model class name.
     *
     * @return string
     */
    protected function model(): string
    {
        return Enrollment::class;
    }

    /**
     * Get enrollments by course.
     *
     * @param string $courseId
     * @return Collection
     * @throws RepositoryException When course not found or database error occurs
     */
    public function getEnrollmentsByCourse(string $courseId): Collection
    {
        try {
            return $this->model->where('course_id', $courseId)
                ->with('student')
                ->get();
        } catch (Exception $e) {
            throw RepositoryException::databaseError($e->getMessage());
        }
    }

    /**
     * Get enrollments by student.
     *
     * @param string $studentId
     * @return Collection
     * @throws RepositoryException When student not found or database error occurs
     */
    public function getEnrollmentsByStudent(string $studentId): Collection
    {
        try {
            return $this->model->where('student_id', $studentId)
                ->with('course')
                ->get();
        } catch (Exception $e) {
            throw RepositoryException::databaseError($e->getMessage());
        }
    }

    /**
     * Check if student is enrolled in course.
     *
     * @param string $studentId
     * @param string $courseId
     * @return bool
     * @throws RepositoryException When database error occurs
     */
    public function isStudentEnrolled(string $studentId, string $courseId): bool
    {
        try {
            return $this->model->where('student_id', $studentId)
                ->where('course_id', $courseId)
                ->exists();
        } catch (Exception $e) {
            throw RepositoryException::databaseError($e->getMessage());
        }
    }

    /**
     * Get recent enrollments.
     *
     * @param int $days
     * @return Collection
     * @throws RepositoryException When database error occurs
     */
    public function getRecentEnrollments(int $days = 7): Collection
    {
        try {
            return $this->model->where('enrolled_at', '>=', Carbon::now()->subDays($days))
                ->with(['student', 'course'])
                ->orderBy('enrolled_at', 'desc')
                ->get();
        } catch (Exception $e) {
            throw RepositoryException::databaseError($e->getMessage());
        }
    }

    /**
     * Get enrollment count by course.
     *
     * @param string $courseId
     * @return int
     * @throws RepositoryException When database error occurs
     */
    public function getEnrollmentCountByCourse(string $courseId): int
    {
        try {
            return $this->model->where('course_id', $courseId)->count();
        } catch (Exception $e) {
            throw RepositoryException::databaseError($e->getMessage());
        }
    }

    /**
     * Get enrollment with course and student details.
     *
     * @param string $enrollmentId
     * @return Model|null
     * @throws RepositoryException When enrollment not found or database error occurs
     */
    public function getEnrollmentWithDetails(string $enrollmentId): ?Model
    {
        try {
            return $this->model->with(['course', 'student'])->find($enrollmentId);
        } catch (Exception $e) {
            throw RepositoryException::databaseError($e->getMessage());
        }
    }

    /**
     * Enroll student in course.
     *
     * @param string $studentId
     * @param string $courseId
     * @return Model
     * @throws RepositoryException When enrollment creation fails or validation errors occur
     */
    public function enrollStudent(string $studentId, string $courseId): Model
    {
        try {
            return $this->create([
                'student_id' => $studentId,
                'course_id' => $courseId,
                'enrolled_at' => Carbon::now()
            ]);
        } catch (Exception $e) {
            throw RepositoryException::databaseError($e->getMessage());
        }
    }
}
