<?php

namespace App\Filament\Pages;

use App\Enums\Status\CourseStatus;
use App\Models\Course;
use App\Models\User;
use BackedEnum;
use Filament\Pages\Page;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class MyCourse extends Page
{
    protected string $view = 'filament.pages.my-course';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-book-open';

    public Collection|EloquentCollection $ongoingCourses;
    public Collection|EloquentCollection $completedCourses;
    public string $searchCourse;
    public array $tags = [];
    public $teachers = [];
    public $selectedTeacher;


    public function mount(): void
    {
        /** @var User $user */
        $now = now();
        $user = Auth::user();
        $enrolledCourses = collect();

        if ($user->hasRole('student')) {
            $enrolledCourses = $this->getEnrolledCourses();

        } else {
            $enrolledCourses = $this->getTeachingCourses();

        }
        $ongoingCourses = collect();
        $completedCourses = collect();
        foreach ($enrolledCourses as $course) {
            $pivot = $course->pivot;

            if ($pivot) { // chỉ student mới có pivot
                if (!$pivot->end_at || $pivot->end_at->isAfter($now)) {
                    $ongoingCourses->push($course);
                } elseif ($pivot->end_at && $pivot->end_at->isBefore($now)) {
                    $completedCourses->push($course);
                }
            } else {
                // teacher: dùng start/end của course trực tiếp
                if (!$course->end_at || $course->end_at->isAfter($now)) {
                    $ongoingCourses->push($course);
                } elseif ($course->end_at && $course->end_at->isBefore($now)) {
                    $completedCourses->push($course);
                }
            }
        }

        foreach ($enrolledCourses as $course) {
            $this->tags = $course->tags()->pluck('name')->unique()->toArray();
        }
        foreach ($enrolledCourses as $course) {
            $this->teachers = User::where('id', $course->teacher_id)->get();
        }
        $this->ongoingCourses = $ongoingCourses;
        $this->completedCourses = $completedCourses;
    }

    public function getLinkToCourseDetail(Course $course): string
    {
        return \App\Filament\Pages\CourseDetail::getUrl(['course' => $course->id]);
    }

    public function getEnrolledCourses()
    {
        /** @var User $user */
        $user = Auth::user();
        $now = now();

        return $user->courses()
            ->where('courses.status', CourseStatus::PUBLISHED)
            ->where(function ($query) use ($now) {
                $query->whereNull('course_user.start_at')
                    ->orWhere('course_user.start_at', '<=', $now);
            })
            ->where(function ($query) use ($now) {
                $query->whereNull('course_user.end_at')
                    ->orWhere('course_user.end_at', '>=', $now);
            })
            ->with(['teacher', 'media', 'tags'])
            ->orderBy('courses.created_at', 'asc')
            ->paginate(10);
    }
    public function getTeachingCourses()
    {
        /** @var User $user */
        $user = Auth::user();
        $now = now();

        return Course::query()
            ->where('teacher_id', $user->id)  // lọc theo giáo viên dạy
            ->where('status', CourseStatus::PUBLISHED)
            ->whereDate('start_at', '<=', $now) // Đã đến ngày bắt đầu
            ->where(function ($query) use ($now) {
                $query->whereNull('end_at')
                    ->orWhereDate('end_at', '>=', $now); // Chưa kết thúc
            })
            ->with(['users', 'media', 'tags'])
            ->orderBy('created_at', 'asc')
            ->get();

    }


    public function searchCourses()
    {
        $ongoingCourses = collect();
        $closedCourses = collect();
        $user = Auth::user();
        if ($user->hasRole('student')) {
            $enrolledCourses = $this->getEnrolledCourses();

        } else {
            $enrolledCourses = $this->getTeachingCourses();

        }


        foreach ($enrolledCourses as $course) {

            if ($this->searchCourse !== '' && !str_contains(strtolower($course->title), strtolower($this->searchCourse))) {
                continue;
            }


            if ($course->status !== CourseStatus::PUBLISHED) {
                $closedCourses->push($course);
                continue;
            }
            $ongoingCourses->push($course);
        }

        $this->ongoingCourses = $ongoingCourses;
        $this->closedCourses = $closedCourses;
    }
    public function sortCourses(string $sort)
    {
        $this->sortBy = $sort;
        $this->filterCourses();
    }
    protected function filterCourses()
    {
        $ongoingCourses = collect();
        $closedCourses = collect();

        $user = Auth::user();
        if ($user->hasRole('student')) {
            $enrolledCourses = $this->getEnrolledCourses();

        } else {
            $enrolledCourses = $this->getTeachingCourses();

        }
        foreach ($enrolledCourses as $course) {
            if ($course->status !== CourseStatus::PUBLISHED) {
                $closedCourses->push($course);
                continue;
            }

            // Course ongoing
            $ongoingCourses->push($course);
        }

        // Áp dụng sắp xếp
        $this->ongoingCourses = $this->sortCollection($ongoingCourses);
        $this->closedCourses = $this->sortCollection($closedCourses);
    }
    protected function sortCollection($collection)
    {
        return match ($this->sortBy) {
            'newest' => $collection->sortByDesc('created_at')->values(),
            'oldest' => $collection->sortBy('created_at')->values(),
            'end_at' => $collection->sortBy(fn($q) => $q->pivot?->end_at ?? now())->values(),
            default => $collection,
        };
    }
    public function filterCoursesByTag($tag = null)
    {
        $ongoingCourses = collect();
        $closedCourses = collect();
        $this->selectedTag = $tag; // lưu tag đã chọn

        $user = Auth::user();
        if ($user->hasRole('student')) {
            $enrolledCourses = $this->getEnrolledCourses();

        } else {
            $enrolledCourses = $this->getTeachingCourses();

        }

        foreach ($enrolledCourses as $course) {

            // Kiểm tra tag
            $hasSelectedTag = $this->selectedTag
                ? $course->tags->contains('name', $this->selectedTag)
                : true;
            if (!$hasSelectedTag) {
                continue;
            }
            if ($course->status !== CourseStatus::PUBLISHED) {
                $closedCourses->push($course);
                continue;
            }


            // Course ongoing
            $ongoingCourses->push($course);
        }

        $this->ongoingCourses = $ongoingCourses;
        $this->closedCourses = $closedCourses;
    }
    public function filterCoursesByTeacher($teacherId)
    {
        $ongoingCourses = collect();
        $closedCourses = collect();
        $this->selectedTeacher = $teacherId;

        $user = Auth::user();
        if ($user->hasRole('student')) {
            $enrolledCourses = $this->getEnrolledCourses();

        } else {
            $enrolledCourses = $this->getTeachingCourses();

        }

        foreach ($enrolledCourses as $course) {


            $hasSelectedTeacher = $this->selectedTeacher
                ? $course->teacher_id == $this->selectedTeacher
                : true;

            if (!$hasSelectedTeacher) {
                continue;
            }

            if ($course->status !== CourseStatus::PUBLISHED) {
                $closedCourses->push($course);
                continue;
            }

            $ongoingCourses->push($course);
        }

        $this->ongoingCourses = $ongoingCourses;
        $this->closedCourses = $closedCourses;
    }


}
