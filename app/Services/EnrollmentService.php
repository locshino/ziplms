<?php

namespace App\Services;

use App\Enums\CourseStatusEnum;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use App\Repositories\Interfaces\EnrollmentRepositoryInterface;
use App\Services\Interfaces\EnrollmentServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Enrollment service implementation.
 *
 * Handles business logic for enrollment operations including
 * enrollment, unenrollment, and enrollment status checking.
 */
class EnrollmentService extends BaseService implements EnrollmentServiceInterface
{
    /**
     * The enrollment repository instance.
     */
    protected EnrollmentRepositoryInterface $enrollmentRepository;

    /**
     * EnrollmentService constructor.
     */
    public function __construct(EnrollmentRepositoryInterface $enrollmentRepository)
    {
        parent::__construct($enrollmentRepository);
        $this->enrollmentRepository = $enrollmentRepository;
    }

    /**
     * Check if user is enrolled in a course.
     */
    public function isUserEnrolledInCourse(string $userId, string $courseId): bool
    {
        return $this->enrollmentRepository->isUserEnrolledInCourse($userId, $courseId);
    }

    /**
     * Get courses enrolled by user.
     */
    public function getEnrolledCoursesByUser(string $userId): Collection
    {
        return $this->enrollmentRepository->getEnrolledCoursesByUser($userId);
    }

    /**
     * Enroll user in a course.
     */
    public function enrollUserInCourse(string $userId, string $courseId): array
    {
        try {
            DB::beginTransaction();

            $course = Course::findOrFail($courseId);
            $user = User::findOrFail($userId);

            // Check if course is published
            if (! $course->hasStatus(CourseStatusEnum::PUBLISHED->value)) {
                return [
                    'success' => false,
                    'message' => 'This course is not available for enrollment.',
                ];
            }

            // Check if course has available spots
            if ($course->max_students > 0 && $course->enrollments()->count() >= $course->max_students) {
                return [
                    'success' => false,
                    'message' => 'This course is full.',
                ];
            }

            // Check if already enrolled
            if ($this->isUserEnrolledInCourse($userId, $courseId)) {
                return [
                    'success' => false,
                    'message' => 'You are already enrolled in this course.',
                ];
            }

            // Create enrollment
            $this->enrollmentRepository->enrollStudent($userId, $courseId);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Successfully enrolled in course.',
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => 'Failed to enroll in course: '.$e->getMessage(),
            ];
        }
    }

    /**
     * Unenroll user from a course.
     */
    public function unenrollUserFromCourse(string $userId, string $courseId): array
    {
        try {
            DB::beginTransaction();

            $enrollment = Enrollment::where('student_id', $userId)
                ->where('course_id', $courseId)
                ->first();

            if (! $enrollment) {
                return [
                    'success' => false,
                    'message' => 'You are not enrolled in this course.',
                ];
            }

            $enrollment->delete();

            DB::commit();

            return [
                'success' => true,
                'message' => 'Successfully unenrolled from course.',
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => 'Failed to unenroll from course: '.$e->getMessage(),
            ];
        }
    }

    /**
     * Get enrollment count for a course.
     */
    public function getEnrollmentCountByCourse(int $courseId): int
    {
        return $this->enrollmentRepository->getEnrollmentCountByCourse($courseId);
    }

    /**
     * Get enrollments by course.
     */
    public function getEnrollmentsByCourse(int $courseId): Collection
    {
        return $this->enrollmentRepository->getEnrollmentsByCourse($courseId);
    }

    /**
     * Get recent enrollments.
     */
    public function getRecentEnrollments(int $days = 7): Collection
    {
        return $this->enrollmentRepository->getRecentEnrollments($days);
    }
}
