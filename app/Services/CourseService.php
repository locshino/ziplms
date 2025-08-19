<?php

namespace App\Services;

use App\Models\Course;
use App\Models\User;
use App\Models\Enrollment;
use App\Enums\CourseStatusEnum;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Services\BaseService;
use App\Services\Interfaces\CourseServiceInterface;
use App\Enums\Permissions\PermissionNounEnum;
use App\Enums\Permissions\PermissionVerbEnum;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use App\Libs\Roles\RoleHelper;

/**
 * Course service implementation.
 *
 * Handles business logic for course operations including CRUD,
 * search, filtering, and permission checking.
 */
class CourseService extends BaseService implements CourseServiceInterface
{
    /**
     * The course repository instance.
     *
     * @var CourseRepositoryInterface
     */
    protected CourseRepositoryInterface $courseRepository;

    /**
     * CourseService constructor.
     */
    public function __construct(CourseRepositoryInterface $courseRepository)
    {
        parent::__construct($courseRepository);
        $this->courseRepository = $courseRepository;
    }

    /**
     * {@inheritDoc}
     */
    public function getPaginatedCourses(
        int $perPage = 15,
        ?string $search = null,
        ?string $teacherId = null,
        ?string $startDateFrom = null,
        ?string $startDateTo = null,
        ?string $status = null,
        ?array $orderBy = null
    ): LengthAwarePaginator {
        return $this->courseRepository->getPaginatedCourses(
            $perPage,
            $search,
            $teacherId,
            $startDateFrom,
            $startDateTo,
            $status,
            $orderBy
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getCoursesByTeacher(string $teacherId): Collection
    {
        return $this->courseRepository->getCoursesByTeacher($teacherId);
    }

    /**
     * {@inheritDoc}
     */
    public function getPublishedCourses(): Collection
    {
        return $this->courseRepository->getPublishedCourses();
    }

    /**
     * {@inheritDoc}
     */
    public function searchCourses(string $search): Collection
    {
        return $this->courseRepository->searchCourses($search);
    }

    /**
     * {@inheritDoc}
     */
    public function getCoursesEnrolledByUser(User $user): Collection
    {
        return $this->courseRepository->getCoursesEnrolledByUser($user->id);
    }

    /**
     * {@inheritDoc}
     */
    public function getAvailableCourses(): Collection
    {
        return $this->courseRepository->getActiveCourses();
    }

    /**
     * {@inheritDoc}
     */
    public function getCompletedCourses(User $user): Collection
    {
        $allCompleted = $this->courseRepository->getCompletedCourses();
        $enrolledCourseIds = Enrollment::where('student_id', $user->id)
            ->pluck('course_id')
            ->toArray();
            
        return $allCompleted->whereNotIn('id', $enrolledCourseIds);
    }

    /**
     * {@inheritDoc}
     */
    public function canUserAccessCourse(User $user, Course $course): bool
    {
        return $this->courseRepository->canUserAccessCourse($user, $course->id);
    }

    /**
     * {@inheritDoc}
     */
    public function getTeachersForFilter(): Collection
    {
        return $this->courseRepository->getTeachersForFilter();
    }

    /**
     * {@inheritDoc}
     */
    public function getCourseStatusesForFilter(): array
    {
        return $this->courseRepository->getCourseStatusesForFilter();
    }

    /**
     * {@inheritDoc}
     */
    public function canUserCreateCourses(User $user): bool
    {
        // Only admin and super admin can create courses
        return RoleHelper::isAdministrative($user) ||
               $user->can(PermissionVerbEnum::CREATE->value . '.' . PermissionNounEnum::COURSE->value);
    }

    /**
     * {@inheritDoc}
     */
    public function canUserEditCourse(User $user, Course $course): bool
    {
        // Administrative users can edit any course
        if (RoleHelper::isAdministrative($user)) {
            return true;
        }

        // Manager can edit courses
        if (RoleHelper::isManager($user)) {
            return true;
        }

        // Check for custom edit permission
        if ($user->can(PermissionVerbEnum::UPDATE->value . '.' . PermissionNounEnum::COURSE->value)) {
            return true;
        }

        // Course teacher can edit their own course
        return $course->teacher_id === $user->id;
    }

    /**
     * {@inheritDoc}
     */
    public function canUserDeleteCourse(User $user, Course $course): bool
    {
        // Only admin and super admin can delete courses
        return RoleHelper::isAdministrative($user) ||
               $user->can(PermissionVerbEnum::DELETE->value . '.' . PermissionNounEnum::COURSE->value);
    }

    /**
     * {@inheritDoc}
     */
    public function canUserRestoreCourse(User $user, Course $course): bool
    {
        // Only admin and super admin can restore courses
        return RoleHelper::isAdministrative($user) ||
               $user->can(PermissionVerbEnum::RESTORE->value . '.' . PermissionNounEnum::COURSE->value);
    }

    /**
     * {@inheritDoc}
     */
    public function getCourseWithDetails(int $courseId): ?Course
    {
        return $this->courseRepository->getCourseWithDetails((string)$courseId);
    }

    /**
     * {@inheritDoc}
     */
    public function getCourseImageUrl(Course $course): string
    {
        if ($course->hasMedia('featured_image')) {
            return $course->getFirstMediaUrl('featured_image', 'thumb');
        }

        // Return a gradient background if no image
        $gradients = [
            'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
            'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
            'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
            'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)',
            'linear-gradient(135deg, #fa709a 0%, #fee140 100%)',
            'linear-gradient(135deg, #a8edea 0%, #fed6e3 100%)',
            'linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%)',
            'linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%)'
        ];

        return $gradients[abs(crc32($course->id)) % count($gradients)];
    }

    /**
     * Get courses that user is enrolled in with pagination.
     * For admin users, returns all courses they can manage (created/teach/manage/enrolled).
     */
    public function getUserEnrolledCourses(string $userId, int $perPage = 8, int $page = 1): array
    {
        $user = User::find($userId);
        
        if (!$user) {
            return [
                'courses' => collect(),
                'total' => 0,
                'current_page' => $page,
                'per_page' => $perPage,
                'last_page' => 0
            ];
        }

        // For administrative users, show all published courses (since they can manage all)
        if (RoleHelper::isAdministrative($user)) {
            $query = Course::whereHas('statuses', function ($statusQuery) {
                $statusQuery->where('name', CourseStatusEnum::PUBLISHED->value);
            });
        } else {
            // For non-admin users, only show enrolled courses
            $query = Course::whereHas('enrollments', function ($q) use ($userId) {
                $q->where('student_id', $userId);
            });
        }

        $total = $query->count();
        $courses = $query->orderBy('created_at', 'desc')
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();

        return [
            'courses' => $courses,
            'total' => $total,
            'current_page' => $page,
            'per_page' => $perPage,
            'last_page' => ceil($total / $perPage)
        ];
    }

    /**
     * Get completed courses for user with pagination.
     */
    public function getUserCompletedCourses(string $userId, int $perPage = 8, int $page = 1): array
    {
        $query = Course::whereHas('enrollments', function ($q) use ($userId) {
            $q->where('student_id', $userId)
              ->whereNotNull('completed_at');
        });

        $total = $query->count();
        $courses = $query->orderBy('created_at', 'desc')
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();

        return [
            'courses' => $courses,
            'total' => $total,
            'current_page' => $page,
            'per_page' => $perPage,
            'last_page' => ceil($total / $perPage)
        ];
    }

    /**
     * Get all published courses with pagination.
     */
    public function getAllPublishedCourses(int $perPage = 8, int $page = 1, ?string $search = null): array
    {
        $query = Course::whereHas('statuses', function ($q) {
            $q->where('name', CourseStatusEnum::PUBLISHED->value);
        });

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $total = $query->count();
        $courses = $query->orderBy('created_at', 'desc')
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();

        return [
            'courses' => $courses,
            'total' => $total,
            'current_page' => $page,
            'per_page' => $perPage,
            'last_page' => ceil($total / $perPage)
        ];
    }

    /**
     * Check if user can view a specific course.
     */
    public function canUserViewCourse(User $user, Course $course): bool
    {
        // Admin can view all courses
        if (RoleHelper::isAdministrative($user)) {
            return true;
        }

        // Teachers can view their own courses
        if (RoleHelper::isTeacher($user) && $course->teacher_id === $user->id) {
            return true;
        }

        // Course managers can view courses they manage
        if ($course->managers()->where('user_id', $user->id)->exists()) {
            return true;
        }

        // Any enrolled user can view published courses they're enrolled in
        if ($course->hasStatus(CourseStatusEnum::PUBLISHED->value)) {
            return $course->enrollments()->where('student_id', $user->id)->exists();
        }

        return false;
    }

    /**
     * Get course progress for a user.
     */
    public function getCourseProgress(Course $course, User $user): int
    {
        $enrollment = $course->enrollments()->where('student_id', $user->id)->first();
        
        if (!$enrollment) {
            return 0;
        }

        // Simple progress calculation - can be enhanced based on assignments, quizzes, etc.
        if ($enrollment->completed_at) {
            return 100;
        }

        // For now, return a basic progress based on enrollment date
        $enrolledDays = now()->diffInDays($enrollment->enrolled_at);
        return min(($enrolledDays * 10), 90); // Max 90% until actually completed
    }

    /**
     * Get the current status of a course
     */
    public function getCurrentStatus(string $courseId): ?string
    {
        return $this->courseRepository->getCurrentStatus($courseId);
    }

    /**
     * Get the current status enum of a course
     */
    public function getCurrentStatusEnum(string $courseId): ?CourseStatusEnum
    {
        return $this->courseRepository->getCurrentStatusEnum($courseId);
    }

    /**
     * Check if course is published
     */
    public function isPublished(string $courseId): bool
    {
        return $this->courseRepository->isPublished($courseId);
    }

    /**
     * Check if course is draft
     */
    public function isDraft(string $courseId): bool
    {
        return $this->courseRepository->isDraft($courseId);
    }

    /**
     * Check if course is archived
     */
    public function isArchived(string $courseId): bool
    {
        return $this->courseRepository->isArchived($courseId);
    }

    /**
     * Check if course is suspended
     */
    public function isSuspended(string $courseId): bool
    {
        return $this->courseRepository->isSuspended($courseId);
    }

    /**
     * Get courses by current status using HasStatuses trait
     */
    public function getCoursesByCurrentStatus(string $status): Collection
    {
        return $this->courseRepository->getCoursesByCurrentStatus($status);
    }

    /**
     * Get courses excluding specific status using HasStatuses trait
     */
    public function getCoursesExcludingStatus(string $status): Collection
    {
        return $this->courseRepository->getCoursesExcludingStatus($status);
    }

    /**
     * Check if course has ever had a specific status
     */
    public function hasEverHadStatus(string $courseId, string $status): bool
    {
        return $this->courseRepository->hasEverHadStatus($courseId, $status);
    }

    /**
     * Check if course has never had a specific status
     */
    public function hasNeverHadStatus(string $courseId, string $status): bool
    {
        return $this->courseRepository->hasNeverHadStatus($courseId, $status);
    }
}