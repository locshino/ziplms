<?php

namespace App\Filament\Pages;

use App\Libs\Roles\RoleHelper;
use App\Models\Course;
use Filament\Pages\Page;

use App\Enums\Status\QuizStatus;
use App\Enums\Status\AssignmentStatus;

class CourseDetail extends Page
{
    protected string $view = 'filament.pages.course-detail';

    protected static bool $shouldRegisterNavigation = false;

    public ?Course $course = null;
    public $documents;
    public $ongoingQuizzes;
    public $selectedDocuments = [];
    public $ongoingAssignments;
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


        if (!$this->course) {
            abort(404);
        }
        $this->documents = $this->course->getMedia('course_documents');
        $now = now();

        $ongoingQuizzes = collect();

        foreach ($this->course->quizzes as $quiz) {
            $start = $quiz->pivot->start_at;
            $end = $quiz->pivot->end_at;

            if ($start && $end && $now->between($start, $end) && $quiz->status === QuizStatus::PUBLISHED) {
                $ongoingQuizzes->push($quiz);
            }
        }
        $addRulesFromOutside = collect();
        foreach ($this->course->assignments as $assignment) {
            $start = $assignment->pivot->start_at;
            $end = $assignment->pivot->end_at;

            if ($start && $end && $now->between($start, $end) && $assignment->status === AssignmentStatus::PUBLISHED) {
                $addRulesFromOutside->push($assignment);
            }
        }


        $this->ongoingQuizzes = $ongoingQuizzes;


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
