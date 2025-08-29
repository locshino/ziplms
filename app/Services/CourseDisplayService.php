<?php

namespace App\Services;

use App\Exceptions\Services\ServiceException;
use App\Models\Course;
use App\Models\User;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Services\Interfaces\CourseDisplayServiceInterface;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Course Display Service
 *
 * Handles course display logic for different tabs and user contexts
 */
class CourseDisplayService implements CourseDisplayServiceInterface
{
    public function __construct(
        protected CourseRepositoryInterface $courseRepository,
        protected CourseService $courseService,
        protected EnrollmentService $enrollmentService
    ) {}

    /**
     * Get courses for "My Courses" tab (enrolled courses)
     */
    public function getMyCourses(User $user, int $perPage = 12, int $currentPage = 1): LengthAwarePaginator
    {
        try {
            $result = $this->courseService->getUserEnrolledCourses($user->id, $perPage, $currentPage);

            // Convert array result to LengthAwarePaginator
            return new LengthAwarePaginator(
                $result['courses'],
                $result['total'],
                $result['per_page'],
                $result['current_page'],
                [
                    'path' => request()->url(),
                    'pageName' => 'page',
                ]
            );
        } catch (Exception $e) {
            throw new ServiceException(
                'exceptions_services.service_error',
                ['message' => "Failed to get enrolled courses for user {$user->id}: ".$e->getMessage()],
                0,
                $e
            );
        }
    }

    /**
     * Get courses for "All Courses" tab (published courses)
     */
    public function getAllCourses(int $perPage = 12, int $currentPage = 1): LengthAwarePaginator
    {
        try {
            $result = $this->courseService->getAllPublishedCourses($perPage, $currentPage);

            // Convert array result to LengthAwarePaginator
            return new LengthAwarePaginator(
                $result['courses'],
                $result['total'],
                $result['per_page'],
                $result['current_page'],
                [
                    'path' => request()->url(),
                    'pageName' => 'page',
                ]
            );
        } catch (Exception $e) {
            throw new ServiceException(
                'exceptions_services.service_error',
                ['message' => 'Failed to get published courses: '.$e->getMessage()],
                0,
                $e
            );
        }
    }

    /**
     * Get courses for "Completed" tab (completed courses)
     */
    public function getCompletedCourses(User $user, int $perPage = 12, int $currentPage = 1): LengthAwarePaginator
    {
        try {
            $result = $this->courseService->getUserCompletedCourses($user->id, $perPage, $currentPage);

            // Convert array result to LengthAwarePaginator
            return new LengthAwarePaginator(
                $result['courses'],
                $result['total'],
                $result['per_page'],
                $result['current_page'],
                [
                    'path' => request()->url(),
                    'pageName' => 'page',
                ]
            );
        } catch (Exception $e) {
            throw new ServiceException(
                'exceptions_services.service_error',
                ['message' => "Failed to get completed courses for user {$user->id}: ".$e->getMessage()],
                0,
                $e
            );
        }
    }

    /**
     * Get course progress for a user
     */
    public function getCourseProgress(Course $course, User $user): int
    {
        try {
            return $this->courseService->getCourseProgress($course, $user);
        } catch (Exception $e) {
            throw new ServiceException(
                'exceptions_services.service_error',
                ['message' => "Failed to get course progress for user {$user->id} in course {$course->id}: ".$e->getMessage()],
                0,
                $e
            );
        }
    }

    /**
     * Enroll user in a course
     */
    public function enrollUserInCourse(int $userId, int $courseId): array
    {
        try {
            return $this->enrollmentService->enrollUserInCourse($userId, (string) $courseId);
        } catch (Exception $e) {
            throw new ServiceException(
                'exceptions_services.service_error',
                ['message' => "Failed to enroll user {$userId} in course {$courseId}: ".$e->getMessage()],
                0,
                $e
            );
        }
    }

    /**
     * Unenroll user from a course
     */
    public function unenrollUserFromCourse(int $userId, int $courseId): array
    {
        try {
            return $this->enrollmentService->unenrollUserFromCourse($userId, (string) $courseId);
        } catch (Exception $e) {
            throw new ServiceException(
                'exceptions_services.service_error',
                ['message' => "Failed to unenroll user {$userId} from course {$courseId}: ".$e->getMessage()],
                0,
                $e
            );
        }
    }

    /**
     * Check if user can view a course
     */
    public function canUserViewCourse(Course $course, User $user): bool
    {
        try {
            return $this->courseService->canUserViewCourse($user, $course);
        } catch (Exception $e) {
            throw new ServiceException(
                'exceptions_services.service_error',
                ['message' => "Failed to check if user {$user->id} can view course {$course->id}: ".$e->getMessage()],
                0,
                $e
            );
        }
    }

    /**
     * Generate course image placeholder based on course ID
     */
    public function generateCourseImagePlaceholder(string $courseId): string
    {
        $colors = [
            '#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FFEAA7',
            '#DDA0DD', '#98D8C8', '#F7DC6F', '#BB8FCE', '#85C1E9',
        ];

        $colorIndex = abs(crc32($courseId)) % count($colors);
        $backgroundColor = $colors[$colorIndex];

        return "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='400' height='200' viewBox='0 0 400 200'%3E%3Crect width='400' height='200' fill='{$backgroundColor}'/%3E%3Ctext x='200' y='100' font-family='Arial, sans-serif' font-size='24' fill='white' text-anchor='middle' dy='.3em'%3ECourse {$courseId}%3C/text%3E%3C/svg%3E";
    }

    /**
     * Get courses by tab type
     */
    public function getCoursesByTab(string $tab, User $user, int $perPage = 12): LengthAwarePaginator
    {
        return match ($tab) {
            'my-courses' => $this->getMyCourses($user, $perPage),
            'all-courses' => $this->getAllCourses($perPage),
            'completed' => $this->getCompletedCourses($user, $perPage),
            default => $this->getAllCourses($perPage)
        };
    }

    /**
     * Check if user is enrolled in a course
     */
    public function isUserEnrolledInCourse(int $userId, int $courseId): bool
    {
        try {
            return $this->enrollmentService->isUserEnrolledInCourse($userId, $courseId);
        } catch (Exception $e) {
            throw new ServiceException(
                'exceptions_services.service_error',
                ['message' => "Failed to check enrollment status for user {$userId} in course {$courseId}: ".$e->getMessage()],
                0,
                $e
            );
        }
    }
}
