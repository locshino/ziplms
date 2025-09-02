<?php

namespace App\Filament\Pages;

use App\Enums\Status\CourseStatus;
use App\Models\Course;
use App\Models\User;
use BackedEnum;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class MyCourse extends Page
{
    use HasPageShield;

    protected string $view = 'filament.pages.my-course';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-book-open';

    public static function getNavigationLabel(): string
    {
        return __('pages.my_course');
    }

    public function getTitle(): string
    {
        return __('pages.my_course');
    }

    // Danh sách khóa học đang học / đã hoàn thành
    public Collection|EloquentCollection $ongoingCourses;

    public Collection|EloquentCollection $completedCourses;

    // Search và lọc
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
        // Nếu là teacher: lấy khóa học đang dạy
        if ($user->hasRole('teacher')) {

            $enrolledCourses = $this->getTeachingCourses();
        } else {
            $enrolledCourses = $this->getEnrolledCourses();
        }
        $ongoingCourses = collect();
        $completedCourses = collect();
        // Phân loại ongoing / completed
        foreach ($enrolledCourses as $course) {
            $pivot = $course->pivot;
            // student mới có pivot

            if ($pivot) {
                if (! $pivot->end_at || $pivot->end_at->isAfter($now)) {
                    $ongoingCourses->push($course);
                } elseif ($pivot->end_at && $pivot->end_at->isBefore($now)) {
                    $completedCourses->push($course);
                }
            } else {
                // teacher: dùng start/end của course trực tiếp
                if (! $course->end_at || $course->end_at->isAfter($now)) {
                    $ongoingCourses->push($course);
                } elseif ($course->end_at && $course->end_at->isBefore($now)) {
                    $completedCourses->push($course);
                }
            }
        }
        // Lấy tất cả tag từ các khóa học

        foreach ($enrolledCourses as $course) {
            $this->tags = $enrolledCourses->flatMap(function ($course) {
                return $course->tags->pluck('name');
            })->unique()->values()->all();
        }
        // Lấy tất cả giáo viên của các khóa học
        foreach ($enrolledCourses as $course) {
            // Lấy tất cả ID giáo viên duy nhất từ các khóa học
            $teacherIds = $enrolledCourses->pluck('teacher_id')->unique();

            // Lấy thông tin của tất cả giáo viên đó trong một câu truy vấn
            $this->teachers = User::whereIn('id', $teacherIds)->get();
        }
        $this->ongoingCourses = $ongoingCourses;
        $this->completedCourses = $completedCourses;
    }

    // Lấy link chi tiết course
    public function getLinkToCourseDetail(Course $course): string
    {
        return \App\Filament\Pages\CourseDetail::getUrl(['course' => $course->id]);
    }

    // Lấy khóa học student đang tham gia
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

    // Lấy khóa học teacher đang dạy
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
    // Tìm kiếm khóa học

    public function searchCourses()
    {
        $ongoingCourses = collect();
        $closedCourses = collect();
        $user = Auth::user();
        if ($user->hasRole('teacher')) {

            $enrolledCourses = $this->getTeachingCourses();
        } else {
            $enrolledCourses = $this->getEnrolledCourses();
        }

        foreach ($enrolledCourses as $course) {
            // Bỏ qua nếu không match search

            if ($this->searchCourse !== '' && ! str_contains(strtolower($course->title), strtolower($this->searchCourse))) {
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

    // Sắp xếp khóa học
    public function sortCourses(string $sort)
    {
        $this->sortBy = $sort;
        $this->filterCourses();
    }

    // Lọc khóa học (dùng cho sorting)
    protected function filterCourses()
    {
        $ongoingCourses = collect();
        $closedCourses = collect();

        $user = Auth::user();
        if ($user->hasRole('teacher')) {

            $enrolledCourses = $this->getTeachingCourses();
        } else {
            $enrolledCourses = $this->getEnrolledCourses();
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

    // Hàm sắp xếp collection theo created_at hoặc end_at
    protected function sortCollection($collection)
    {
        return match ($this->sortBy) {
            'newest' => $collection->sortByDesc('created_at')->values(),
            'oldest' => $collection->sortBy('created_at')->values(),
            'end_at' => $collection->sortBy(fn ($q) => $q->pivot?->end_at ?? now())->values(),
            default => $collection,
        };
    }

    // Lọc khóa học theo tag
    public function filterCoursesByTag($tag = null)
    {
        $ongoingCourses = collect();
        $closedCourses = collect();
        $this->selectedTag = $tag; // lưu tag đã chọn

        $user = Auth::user();
        if ($user->hasRole('teacher')) {

            $enrolledCourses = $this->getTeachingCourses();
        } else {
            $enrolledCourses = $this->getEnrolledCourses();
        }

        foreach ($enrolledCourses as $course) {

            // Kiểm tra tag
            $hasSelectedTag = $this->selectedTag
                ? $course->tags->contains('name', $this->selectedTag)
                : true;
            if (! $hasSelectedTag) {
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

    // Lọc khóa học theo teacher
    public function filterCoursesByTeacher($teacherId)
    {

        $ongoingCourses = collect();
        $closedCourses = collect();
        $this->selectedTeacher = $teacherId;
        $user = Auth::user();
        if ($user->hasRole('teacher')) {

            $enrolledCourses = $this->getTeachingCourses();
        } else {
            $enrolledCourses = $this->getEnrolledCourses();
        }

        foreach ($enrolledCourses as $course) {

            $hasSelectedTeacher = $this->selectedTeacher
                ? $course->teacher_id == $this->selectedTeacher
                : true;

            if (! $hasSelectedTeacher) {
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
