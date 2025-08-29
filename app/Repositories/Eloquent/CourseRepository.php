<?php

namespace App\Repositories\Eloquent;

use App\Enums\CourseStatusEnum;
use App\Models\Course;
use App\Models\User;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\States\Status;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class CourseRepository
 */
class CourseRepository extends EloquentRepository implements CourseRepositoryInterface
{
    protected function model(): string
    {
        return Course::class;
    }

    public function getCourseStatusLabel(Course $course): string
    {
        /** @var Status $statusState */
        $statusState = $course->status;

        return $statusState::label();
    }

    public function getCourseStatusColor(Course $course): string
    {
        return $course->status->color();
    }

    public function getAvailableParents(?int $excludeId = null): Collection
    {
        return $this->model->query()
            ->when($excludeId, fn ($query) => $query->where('id', '!=', $excludeId))
            ->pluck('title', 'id');
    }

    public function applyDateFilter(Builder $query, ?string $from, ?string $to): Builder
    {
        return $query
            ->when(
                $from,
                fn (Builder $q, $fromDate): Builder => $q->where('created_at', '>=', Carbon::parse($fromDate)->startOfDay()),
            )
            ->when(
                $to,
                fn (Builder $q, $untilDate): Builder => $q->where('created_at', '<=', Carbon::parse($untilDate)->endOfDay()),
            );
    }

    public function getPaginatedCourses(
        int $perPage = 15,
        ?string $search = null,
        ?string $teacherId = null,
        ?string $startDateFrom = null,
        ?string $startDateTo = null,
        ?string $status = null,
        ?array $orderBy = null
    ): LengthAwarePaginator {
        $query = $this->model->query()->with(['teacher', 'enrollments']);

        // Apply search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Apply teacher filter
        if ($teacherId) {
            $query->where('teacher_id', $teacherId);
        }

        // Apply start date filter
        if ($startDateFrom) {
            $query->where('start_date', '>=', Carbon::parse($startDateFrom));
        }
        if ($startDateTo) {
            $query->where('start_date', '<=', Carbon::parse($startDateTo));
        }

        // Apply status filter using scopeCurrentStatus from HasStatuses trait
        if ($status) {
            $query->currentStatus($status);
        }

        // Apply ordering
        if ($orderBy && is_array($orderBy)) {
            foreach ($orderBy as $column => $direction) {
                $query->orderBy($column, $direction);
            }
        } else {
            $query->orderBy('start_date', 'asc')
                ->orderBy('created_at', 'asc');
        }

        return $query->paginate($perPage);
    }

    public function getCoursesByTeacher(string $teacherId): Collection
    {
        return $this->model->where('teacher_id', $teacherId)
            ->with(['enrollments'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getPublishedCourses(): Collection
    {
        // Use scopeCurrentStatus from HasStatuses trait for better performance
        return $this->model->currentStatus(CourseStatusEnum::PUBLISHED->value)
            ->with(['teacher', 'enrollments'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getCoursesWithEnrollmentsCount(): Collection
    {
        return $this->model->withCount('enrollments')
            ->with(['teacher'])
            ->orderBy('enrollments_count', 'desc')
            ->get();
    }

    public function searchCourses(string $search): Collection
    {
        return $this->model->where(function ($query) use ($search) {
            $query->where('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        })
            ->with(['teacher', 'enrollments'])
            ->orderBy('title')
            ->get();
    }

    public function getCoursesByCategory(string $category): Collection
    {
        return $this->model->where('category', $category)
            ->with(['teacher', 'enrollments'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getCourseWithDetails(string $courseId): ?Course
    {
        return $this->model->with([
            'teacher',
            'enrollments.student',
            'assignments',
            'quizzes',
        ])
            ->find($courseId);
    }

    public function getAllCoursesForPage(
        int $perPage = 8,
        int $page = 1,
        ?string $search = null,
        ?string $teacherId = null,
        ?string $startDateFrom = null,
        ?string $startDateTo = null,
        ?string $status = null
    ): array {
        $query = $this->model->query()->with(['teacher', 'enrollments']);

        // Filter active courses (not ended or no end date)
        $query->where(function ($q) {
            $q->where('end_date', '>', now())
                ->orWhereNull('end_date');
        });

        // Apply filters
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($teacherId) {
            $query->where('teacher_id', $teacherId);
        }

        if ($startDateFrom) {
            $query->where('start_date', '>=', Carbon::parse($startDateFrom));
        }
        if ($startDateTo) {
            $query->where('start_date', '<=', Carbon::parse($startDateTo));
        }

        if ($status) {
            // Use scopeCurrentStatus from HasStatuses trait
            $query->currentStatus($status);
        }

        // Order by start_date and created_at
        $query->orderBy('start_date', 'asc')
            ->orderBy('created_at', 'asc');

        $total = $query->count();
        $courses = $query->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();

        return [
            'courses' => $courses,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => ceil($total / $perPage),
        ];
    }

    public function getCoursesByUserPermissions(User $user): Collection
    {
        $query = $this->model->query()->with(['teacher', 'enrollments']);

        if ($user->hasRole('student')) {
            // Students can only see courses they are enrolled in
            $enrolledCourseIds = $user->enrollments()->pluck('course_id');
            $query->whereIn('id', $enrolledCourseIds);
        } elseif ($user->hasRole('teacher')) {
            // Teachers can see courses they are assigned to teach
            $query->where('teacher_id', $user->id);
        }
        // Managers, admins, and super admins can see all courses

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function getActiveCourses(): Collection
    {
        return $this->model->where(function ($query) {
            $query->where('end_date', '>', now())
                ->orWhereNull('end_date');
        })
            ->with(['teacher', 'enrollments'])
            ->orderBy('start_date', 'asc')
            ->get();
    }

    public function getCompletedCourses(): Collection
    {
        return $this->model->where('end_date', '<=', now())
            ->with(['teacher', 'enrollments'])
            ->orderBy('end_date', 'desc')
            ->get();
    }

    public function getCoursesEnrolledByUser(string $userId): Collection
    {
        return $this->model->whereHas('enrollments', function ($query) use ($userId) {
            $query->where('student_id', $userId);
        })
            ->with(['teacher', 'enrollments'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function canUserAccessCourse(User $user, string $courseId): bool
    {
        if ($user->hasRole(['admin', 'super_admin', 'manager'])) {
            return true;
        }

        if ($user->hasRole('teacher')) {
            return $this->model->where('id', $courseId)
                ->where('teacher_id', $user->id)
                ->exists();
        }

        if ($user->hasRole('student')) {
            return $user->enrollments()
                ->where('course_id', $courseId)
                ->exists();
        }

        return false;
    }

    public function getTeachersForFilter(): Collection
    {
        return User::whereHas('roles', function ($query) {
            $query->where('name', 'teacher');
        })
            ->select('id', 'name')
            ->orderBy('name')
            ->get();
    }

    public function getCourseStatusesForFilter(): array
    {
        return [
            CourseStatusEnum::DRAFT->value => CourseStatusEnum::DRAFT->label(),
            CourseStatusEnum::PUBLISHED->value => CourseStatusEnum::PUBLISHED->label(),
            CourseStatusEnum::ARCHIVED->value => CourseStatusEnum::ARCHIVED->label(),
            CourseStatusEnum::SUSPENDED->value => CourseStatusEnum::SUSPENDED->label(),
        ];
    }

    /**
     * Get the current status of a course using HasStatuses trait
     */
    public function getCurrentStatus(string $courseId): ?string
    {
        $course = $this->findById($courseId);
        if (! $course) {
            return null;
        }

        // Use latestStatus() method from HasStatuses trait
        $latestStatus = $course->latestStatus();

        return $latestStatus ? $latestStatus->name : null;
    }

    /**
     * Get the current status enum of a course
     */
    public function getCurrentStatusEnum(string $courseId): ?CourseStatusEnum
    {
        $status = $this->getCurrentStatus($courseId);

        return $status ? CourseStatusEnum::tryFrom($status) : null;
    }

    /**
     * Check if course is published using HasStatuses trait
     */
    public function isPublished(string $courseId): bool
    {
        $course = $this->findById($courseId);

        // Use hasStatus() method from HasStatuses trait
        return $course ? $course->hasStatus(CourseStatusEnum::PUBLISHED->value) : false;
    }

    /**
     * Check if course is draft using HasStatuses trait
     */
    public function isDraft(string $courseId): bool
    {
        $course = $this->findById($courseId);

        return $course ? $course->hasStatus(CourseStatusEnum::DRAFT->value) : false;
    }

    /**
     * Check if course is archived using HasStatuses trait
     */
    public function isArchived(string $courseId): bool
    {
        $course = $this->findById($courseId);

        return $course ? $course->hasStatus(CourseStatusEnum::ARCHIVED->value) : false;
    }

    /**
     * Check if course is suspended using HasStatuses trait
     */
    public function isSuspended(string $courseId): bool
    {
        $course = $this->findById($courseId);

        return $course ? $course->hasStatus(CourseStatusEnum::SUSPENDED->value) : false;
    }

    /**
     * Get courses by current status using scopeCurrentStatus from HasStatuses trait
     */
    public function getCoursesByCurrentStatus(string $status): Collection
    {
        return $this->model->currentStatus($status)
            ->with(['teacher', 'enrollments'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get courses that don't have specific status using scopeOtherCurrentStatus from HasStatuses trait
     */
    public function getCoursesExcludingStatus(string $status): Collection
    {
        return $this->model->otherCurrentStatus($status)
            ->with(['teacher', 'enrollments'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Check if course has ever had a specific status using HasStatuses trait
     */
    public function hasEverHadStatus(string $courseId, string $status): bool
    {
        $course = $this->findById($courseId);

        return $course ? $course->hasEverHadStatus($status) : false;
    }

    /**
     * Check if course has never had a specific status using HasStatuses trait
     */
    public function hasNeverHadStatus(string $courseId, string $status): bool
    {
        $course = $this->findById($courseId);

        return $course ? $course->hasNeverHadStatus($status) : false;
    }
}
