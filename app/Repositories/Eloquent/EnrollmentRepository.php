<?php

namespace App\Repositories\Eloquent;

use App\Models\Enrollment;
use App\Repositories\Interfaces\EnrollmentRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class EnrollmentRepository extends EloquentRepository implements EnrollmentRepositoryInterface
{
    /**
     * Get the model class name.
     */
    protected function model(): string
    {
        return Enrollment::class;
    }

    /**
     * Lấy các khóa học mà user đã đăng ký
     */
    public function getEnrolledCoursesByUser(string $userId): Collection
    {
        return $this->model
            ->where('student_id', $userId)
            ->with('course')
            ->get()
            ->pluck('course')
            ->filter(); // Remove null values
    }

    /**
     * Kiểm tra user có đăng ký khóa học không
     */
    public function isUserEnrolledInCourse(string $userId, string $courseId): bool
    {
        return $this->model
            ->where('student_id', $userId)
            ->where('course_id', $courseId)
            ->exists();
    }

    /**
     * Lấy đăng ký theo khóa học
     */
    public function getEnrollmentsByCourse(int $courseId): Collection
    {
        return $this->model
            ->where('course_id', $courseId)
            ->with(['student', 'course'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Lấy đăng ký theo học sinh
     */
    public function getEnrollmentsByStudent(string $studentId): Collection
    {
        return $this->model
            ->where('student_id', $studentId)
            ->with(['student', 'course'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Đăng ký student vào course
     */
    public function enrollStudent(string $studentId, string $courseId, array $additionalData = []): Enrollment
    {
        $data = array_merge([
            'student_id' => $studentId,
            'course_id' => $courseId,
            'enrolled_at' => now(),
        ], $additionalData);

        return $this->model->create($data);
    }

    /**
     * Lấy đăng ký gần đây
     */
    public function getRecentEnrollments(int $days = 7): Collection
    {
        $fromDate = Carbon::now()->subDays($days);

        return $this->model
            ->where('created_at', '>=', $fromDate)
            ->with(['student', 'course'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Đếm số đăng ký theo khóa học
     */
    public function getEnrollmentCountByCourse(int $courseId): int
    {
        return $this->model
            ->where('course_id', $courseId)
            ->count();
    }

    /**
     * Lấy đăng ký với thông tin chi tiết
     */
    public function getEnrollmentWithDetails(int $enrollmentId): ?Enrollment
    {
        return $this->model
            ->with(['student', 'course'])
            ->find($enrollmentId);
    }

    /**
     * Delete enrollment by student and course
     */
    public function deleteEnrollment(int $studentId, int $courseId): bool
    {
        return $this->model
            ->where('student_id', $studentId)
            ->where('course_id', $courseId)
            ->delete() > 0;
    }

    /**
     * Get enrolled user IDs for a course
     */
    public function getEnrolledUserIds(string $courseId): Collection
    {
        return $this->model
            ->where('course_id', $courseId)
            ->pluck('student_id');
    }

    /**
     * Get user enrollments excluding a specific course
     */
    public function getUserEnrollmentsExcludingCourse(int $userId, int $excludeCourseId): Collection
    {
        return $this->model
            ->where('student_id', $userId)
            ->where('course_id', '!=', $excludeCourseId)
            ->with(['course'])
            ->get();
    }
}
