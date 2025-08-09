<?php

namespace App\Repositories\Interfaces;

use App\Models\Enrollment;
use Illuminate\Support\Collection;

interface EnrollmentRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * Lấy các khóa học mà user đã đăng ký
     */
    public function getEnrolledCoursesByUser(string $userId): Collection;

    /**
     * Kiểm tra user có đăng ký khóa học không
     */
    public function isUserEnrolledInCourse(string $userId, string $courseId): bool;

    /**
     * Lấy đăng ký theo khóa học
     */
    public function getEnrollmentsByCourse(int $courseId): Collection;

    /**
     * Lấy đăng ký theo học sinh
     */
    public function getEnrollmentsByStudent(string $studentId): Collection;

    /**
     * Đăng ký học sinh vào khóa học
     */
    public function enrollStudent(string $studentId, string $courseId, array $additionalData = []): Enrollment;

    /**
     * Lấy đăng ký gần đây
     */
    public function getRecentEnrollments(int $days = 7): Collection;

    /**
     * Đếm số đăng ký theo khóa học
     */
    public function getEnrollmentCountByCourse(int $courseId): int;

    /**
     * Lấy đăng ký với thông tin chi tiết
     */
    public function getEnrollmentWithDetails(int $enrollmentId): ?Enrollment;
}
