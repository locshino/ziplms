<?php

namespace App\Filament\Pages;

use App\Libs\Roles\RoleHelper;
use App\Models\Course;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Collection;

class CourseDetail extends Page
{
    protected string $view = 'filament.pages.course-detail';

    protected static bool $shouldRegisterNavigation = false;

    public ?Course $course = null;

    public function mount($course = null): void
    {
        // Resolve identifier from argument or route / request
        $identifier = $course ?? request()->route('course') ?? request()->get('course');

        // If a Course model was injected, use it
        if ($identifier instanceof Course) {
            $this->course = $identifier->load(['quizzes', 'assignments']);
            return;
        }

        // If identifier is an array (e.g. accidental payload), try to extract an id
        if (is_array($identifier)) {
            $identifier = $identifier['id'] ?? ($identifier[0] ?? null);
        }

        // Ensure we query for a single model (first), not a collection
        $this->course = Course::with(['quizzes', 'assignments'])
            ->whereKey($identifier)
            ->orWhere('slug', $identifier)
            ->orWhere('uuid', $identifier)
            ->first();

        if (! $this->course) {
            abort(404);
        }

    }

    public function getTitle(): string
    {
        return $this->course?->title ?? 'Course Details';
    }

    public static function canAccess(): bool
    {
        return RoleHelper::isLMSUsers();
    }
}
