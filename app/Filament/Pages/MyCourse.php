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

    // Danh sách khóa học
    public Collection|EloquentCollection $ongoingCourses;
    public Collection|EloquentCollection $completedCourses;

    // Thuộc tính cho việc lọc và tìm kiếm
    public string $searchCourse = '';
    public array $tags = [];
    public Collection $teachers;
    public $selectedTeacher;
    public $sortBy = 'newest';
    public $selectedTag;

    public function mount(): void
    {
        /** @var User $user */
        $user = Auth::user();
        $allEnrolledCourses = $this->getBaseQuery();

        // Lấy danh sách tags và teachers từ tất cả khóa học của user trước khi lọc
        if ($allEnrolledCourses->isNotEmpty()) {
            $this->tags = $allEnrolledCourses->flatMap(fn($course) => $course->tags->pluck('name'))->unique()->values()->all();
            $teacherIds = $allEnrolledCourses->pluck('teacher_id')->unique();
            $this->teachers = User::whereIn('id', $teacherIds)->get();
        } else {
            $this->teachers = collect();
        }

        // Tải và phân loại khóa học lần đầu
        $this->filterAndSortCourses();
    }

    /**
     * Lấy query cơ sở cho các khóa học của user (chưa lọc, chưa sắp xếp)
     */
    private function getBaseQuery(): Collection|EloquentCollection
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->hasRole('teacher')) {
            return Course::query()
                ->where('teacher_id', $user->id)
                ->where('status', CourseStatus::PUBLISHED)
                ->with(['users', 'media', 'tags'])
                ->get();
        }

        return $user->courses()
            ->where('courses.status', CourseStatus::PUBLISHED)
            ->with(['teacher', 'media', 'tags'])
            ->get(); // Sửa từ paginate() thành get() là quan trọng nhất
    }


    /**
     * Hàm trung tâm để áp dụng tất cả các bộ lọc và sắp xếp
     */
    protected function filterAndSortCourses()
    {
        $now = now();
        $queryResult = $this->getBaseQuery();

        // 1. Áp dụng tìm kiếm
        if (!empty($this->searchCourse)) {
            $queryResult = $queryResult->filter(
                fn($course) => str_contains(strtolower($course->title), strtolower($this->searchCourse))
            );
        }

        // 2. Áp dụng lọc theo tag
        if (!empty($this->selectedTag)) {
            $queryResult = $queryResult->filter(
                fn($course) => $course->tags->contains('name', $this->selectedTag)
            );
        }

        // 3. Áp dụng lọc theo giáo viên
        if (!empty($this->selectedTeacher)) {
            $queryResult = $queryResult->filter(
                fn($course) => $course->teacher_id == $this->selectedTeacher
            );
        }

        // 4. Phân loại thành ongoing và completed
        $ongoing = collect();
        $completed = collect();

        foreach ($queryResult as $course) {
            $endDate = $course->pivot->end_at ?? $course->end_at;
            if (!$endDate || $endDate->isAfter($now)) {
                $ongoing->push($course);
            } else {
                $completed->push($course);
            }
        }

        // 5. Sắp xếp các collection đã phân loại
        $this->ongoingCourses = $this->sortCollection($ongoing);
        $this->completedCourses = $this->sortCollection($completed);
    }

    /**
     * Hàm sắp xếp một collection dựa trên thuộc tính sortBy
     */
    protected function sortCollection($collection)
    {
        return match ($this->sortBy) {
            'newest' => $collection->sortByDesc('created_at')->values(),
            'oldest' => $collection->sortBy('created_at')->values(),
            'end_at' => $collection->sortBy(fn($q) => $q->pivot?->end_at ?? $q->end_at ?? now())->values(),
            default => $collection,
        };
    }

    /**
     * Lấy link chi tiết course
     */
    public function getLinkToCourseDetail(Course $course): string
    {
        return \App\Filament\Pages\CourseDetail::getUrl(['course' => $course->id]);
    }

    // Các hàm này sẽ được gọi từ view (thông qua wire:model, wire:click)
    public function searchCourses()
    {
        $this->filterAndSortCourses();
    }

    public function sortCourses(string $sort)
    {
        $this->sortBy = $sort;
        $this->filterAndSortCourses();
    }

    public function filterCoursesByTag($tag = null)
    {
        $this->selectedTag = $tag;
        $this->filterAndSortCourses();
    }

    public function filterCoursesByTeacher($teacherId)
    {
        $this->selectedTeacher = $teacherId;
        $this->filterAndSortCourses();
    }
}