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
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    public Collection|EloquentCollection $ongoingCourses;
    public Collection|EloquentCollection $completedCourses;

    public function mount(): void
    {
        /** @var User $user */
        $now = now();

        $enrolledCourses = $this->getEnrolledCourses();

        $ongoingCourses = collect();
        $completedCourses = collect();

        foreach ($enrolledCourses as $course) {
            $pivot = $course->pivot;
            if (! $pivot->end_at || $pivot->end_at->isAfter($now)) {
                $ongoingCourses->push($course);
            } elseif ($pivot->end_at && $pivot->end_at->isBefore($now)) {
                $completedCourses->push($course);
            }
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
}
