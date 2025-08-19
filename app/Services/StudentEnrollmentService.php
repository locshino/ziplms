<?php

namespace App\Services;

use App\Exceptions\Services\ServiceException;
use App\Models\Course;
use App\Models\User;
use App\Models\Enrollment;
use App\Models\Permission;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\EnrollmentRepositoryInterface;
use App\Services\Interfaces\StudentEnrollmentServiceInterface;
use App\Services\PermissionService;
use App\Libs\Permissions\PermissionHelper;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Exception;

/**
 * Student Enrollment Service
 * 
 * Handles student enrollment operations for courses
 */
class StudentEnrollmentService implements StudentEnrollmentServiceInterface
{
    public function __construct(
        protected CourseRepositoryInterface $courseRepository,
        protected UserRepositoryInterface $userRepository,
        protected EnrollmentRepositoryInterface $enrollmentRepository,
        protected PermissionService $permissionService
    ) {}

    /**
     * Get enrolled students for a course
     */
    public function getEnrolledStudents(Course $course): Collection
    {
        try {
            return $this->enrollmentRepository->getEnrollmentsByCourse($course->id);
        } catch (Exception $e) {
            throw new ServiceException(
                'exceptions_services.service_error',
                ['message' => "Failed to get enrolled students for course {$course->id}: " . $e->getMessage()],
                0,
                $e
            );
        }
    }

    /**
     * Bulk enroll students in a course
     */
    public function bulkEnrollStudents(
        Course $course, 
        array $studentIds, 
        ?Carbon $enrollmentDate = null, 
        ?string $notes = null
    ): array {
        try {
            DB::beginTransaction();

            $enrollmentDate = $enrollmentDate ?? now();
            $enrolledCount = 0;
            $skippedCount = 0;
            $errors = [];

            // Ensure permissions exist
            $this->ensureEnrollmentPermissionsExist($course);

            foreach ($studentIds as $studentId) {
                try {
                    $user = $this->userRepository->findById($studentId);
                    if (!$user) {
                        $errors[] = "User with ID {$studentId} not found";
                        $skippedCount++;
                        continue;
                    }

                    // Check if already enrolled
                    if ($this->enrollmentRepository->isUserEnrolledInCourse($studentId, $course->id)) {
                        $errors[] = "User {$user->name} is already enrolled";
                        $skippedCount++;
                        continue;
                    }

                    // Create enrollment
                    $this->enrollmentRepository->create([
                        'course_id' => $course->id,
                        'student_id' => $studentId,
                        'enrolled_at' => $enrollmentDate,
                        'status' => 'active',
                        'notes' => $notes,
                    ]);

                    // Grant permissions
                    $this->grantEnrollmentPermissions($user, $course);

                    $enrolledCount++;
                } catch (Exception $e) {
                    $errors[] = "Failed to enroll user {$studentId}: " . $e->getMessage();
                    $skippedCount++;
                }
            }

            DB::commit();

            return [
                'success' => true,
                'enrolled_count' => $enrolledCount,
                'skipped_count' => $skippedCount,
                'errors' => $errors,
                'message' => $this->generateEnrollmentMessage($enrolledCount, $skippedCount)
            ];
        } catch (Exception $e) {
            DB::rollBack();
            throw new ServiceException(
                'exceptions_services.service_error',
                ['message' => "Failed to bulk enroll students in course {$course->id}: " . $e->getMessage()],
                0,
                $e
            );
        }
    }

    /**
     * Unenroll a student from a course
     */
    public function unenrollStudent(Course $course, int $studentId): array
    {
        try {
            DB::beginTransaction();

            $user = $this->userRepository->findById($studentId);
            if (!$user) {
                throw new ServiceException(
                    'exceptions_services.service_error',
                    ['message' => "User with ID {$studentId} not found"]
                );
            }

            // Check if enrolled
            if (!$this->enrollmentRepository->isUserEnrolledInCourse($studentId, $course->id)) {
                throw new ServiceException(
                    'exceptions_services.service_error',
                    ['message' => "User {$user->name} is not enrolled in this course"]
                );
            }

            // Remove enrollment
            $this->enrollmentRepository->deleteEnrollment($studentId, $course->id);

            // Revoke permissions
            $this->revokeEnrollmentPermissions($user, $course);

            DB::commit();

            return [
                'success' => true,
                'message' => "Student {$user->name} has been unenrolled from the course"
            ];
        } catch (Exception $e) {
            DB::rollBack();
            throw new ServiceException(
                'exceptions_services.service_error',
                ['message' => "Failed to unenroll student from course {$course->id}: " . $e->getMessage()],
                0,
                $e
            );
        }
    }

    /**
     * Get available students for enrollment
     */
    public function getAvailableStudents(Course $course): Collection
    {
        try {
            // Get users who are not teachers, managers, or already enrolled
            $excludedUserIds = collect();
            
            // Exclude teachers
            $teachers = $this->userRepository->getUsersByRole('teacher');
            $excludedUserIds = $excludedUserIds->merge($teachers->pluck('id'));
            
            // Exclude course managers
            $managers = $this->getCourseManagers($course);
            $excludedUserIds = $excludedUserIds->merge($managers->pluck('id'));
            
            // Exclude already enrolled students
            $enrolled = $this->enrollmentRepository->getEnrolledUserIds($course->id);
            $excludedUserIds = $excludedUserIds->merge($enrolled);
            
            return $this->userRepository->getUsersNotInIds($excludedUserIds->unique()->toArray());
        } catch (Exception $e) {
            throw new ServiceException(
                'exceptions_services.service_error',
                ['message' => "Failed to get available students for course {$course->id}: " . $e->getMessage()],
                0,
                $e
            );
        }
    }

    /**
     * Ensure enrollment permissions exist for a course
     */
    protected function ensureEnrollmentPermissionsExist(Course $course): void
    {
        $permissions = array_merge(
            $this->getEnrolledPermissions(),
            $this->getCourseSpecificPermissions($course)
        );

        foreach ($permissions as $permission) {
            $this->permissionService->ensurePermissionExists($permission);
        }
    }

    /**
     * Grant enrollment permissions to user
     */
    protected function grantEnrollmentPermissions(User $user, Course $course): void
    {
        // Grant enrolled permissions
        foreach ($this->getEnrolledPermissions() as $permission) {
            if (!$user->hasPermissionTo($permission)) {
                $user->givePermissionTo($permission);
            }
        }

        // Grant course-specific permissions
        foreach ($this->getCourseSpecificPermissions($course) as $permission) {
            if (!$user->hasPermissionTo($permission)) {
                $user->givePermissionTo($permission);
            }
        }
    }

    /**
     * Revoke enrollment permissions from user
     */
    protected function revokeEnrollmentPermissions(User $user, Course $course): void
    {
        // Revoke course-specific permissions
        foreach ($this->getCourseSpecificPermissions($course) as $permission) {
            if ($user->hasPermissionTo($permission)) {
                $user->revokePermissionTo($permission);
            }
        }

        // Check if user is enrolled in other courses before revoking general enrolled permissions
        $otherEnrollments = $this->enrollmentRepository->getUserEnrollmentsExcludingCourse(
            $user->id, 
            $course->id
        );

        if ($otherEnrollments->isEmpty()) {
            foreach ($this->getEnrolledPermissions() as $permission) {
                if ($user->hasPermissionTo($permission)) {
                    $user->revokePermissionTo($permission);
                }
            }
        }
    }

    /**
     * Get general enrolled permissions
     */
    protected function getEnrolledPermissions(): array
    {
        return [
            PermissionHelper::make()->view()->course()->enrolled()->build(),
            PermissionHelper::make()->view()->assignment()->enrolled()->build(),
            PermissionHelper::make()->submit()->assignment()->enrolled()->build(),
            PermissionHelper::make()->view()->quiz()->enrolled()->build(),
            PermissionHelper::make()->attempt()->quiz()->enrolled()->build(),
        ];
    }

    /**
     * Get course-specific permissions
     */
    protected function getCourseSpecificPermissions(Course $course): array
    {
        return [
            PermissionHelper::make()->view()->course()->id($course->id)->build(),
            PermissionHelper::make()->view()->assignment()->id($course->id)->build(),
            PermissionHelper::make()->submit()->assignment()->id($course->id)->build(),
            PermissionHelper::make()->view()->quiz()->id($course->id)->build(),
            PermissionHelper::make()->attempt()->quiz()->id($course->id)->build(),
        ];
    }

    /**
     * Get course managers
     */
    protected function getCourseManagers(Course $course): Collection
    {
        try {
            $permissionName = PermissionHelper::make()
                ->manage()
                ->course()
                ->id($course->id)
                ->build();
                
            // Check if permission exists first
            $permission = Permission::where('name', $permissionName)->first();
            if (!$permission) {
                // Permission doesn't exist, return empty collection
                return collect();
            }
                
            return $this->userRepository->getUsersWithPermission($permissionName);
        } catch (Exception $e) {
            // If there's any error, return empty collection to avoid breaking the flow
            return collect();
        }
    }

    /**
     * Generate enrollment result message
     */
    protected function generateEnrollmentMessage(int $enrolledCount, int $skippedCount): string
    {
        if ($enrolledCount > 0 && $skippedCount > 0) {
            return "Successfully enrolled {$enrolledCount} students. {$skippedCount} students were skipped.";
        } elseif ($enrolledCount > 0) {
            return "Successfully enrolled {$enrolledCount} students.";
        } else {
            return "No students were enrolled.";
        }
    }
}