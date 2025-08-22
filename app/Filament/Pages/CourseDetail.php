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
    public $completedQuizzes = [];
    public $search = '';
    public $courseTags;
    public $closedAssignments;
    public $quizTags;

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
        $completedQuizzes = collect();
        foreach ($this->course->quizzes as $quiz) {
            $start = $quiz->pivot->start_at;
            $end = $quiz->pivot->end_at;

            if ($quiz->status === QuizStatus::ARCHIVED || $quiz->status === QuizStatus::CLOSED) {
                // Đã làm
                $completedQuizzes->push($quiz);
            } elseif (
                $start &&
                $now->gte($start) &&   // đã bắt đầu (start nhỏ hơn now)
                (!$end || $now->lte($end)) &&  // chưa kết thúc hoặc không có end
                $quiz->status === QuizStatus::PUBLISHED
            ) {
                // Đang mở
                $ongoingQuizzes->push($quiz);
            }
        }

        // Assignments
        $ongoingAssignments = collect();
        $closedAssignments = collect();
        foreach ($this->course->assignments as $assignment) {
            $start = $assignment->pivot->start_at;
            $end = $assignment->pivot->end_at;

            if ($start && $now->gte($start) && $assignment->status === AssignmentStatus::PUBLISHED) {
                if (!$end || $now->lte($end)) {
                    $ongoingAssignments->push($assignment);
                }
            }
        }


        foreach ($this->course->assignments as $assignment) {
            $start = $assignment->pivot->start_at;
            $end = $assignment->pivot->end_at;
            if ($assignment->status == AssignmentStatus::DRAFT) {
                continue;
            }
            if ($assignment->status !== AssignmentStatus::PUBLISHED) {
                if ($assignment->status !== AssignmentStatus::DRAFT) {
                    $closedAssignments->push($assignment);
                }


                continue;
            }

            if (!$start) {
                continue;
            }

            if ($now->gt($start) && (!$end || $now->lte($end))) {
                // Bài đang mở
                $ongoingAssignments->push($assignment);
            }
        }

        $this->ongoingQuizzes = $ongoingQuizzes;
        $this->completedQuizzes = $completedQuizzes;
        $this->ongoingAssignments = $ongoingAssignments;
        $this->closedAssignments = $closedAssignments;
        $this->courseTags = $this->course->assignments
            ->flatMap(fn($assignment) => $assignment->tags->pluck('name'))
            ->unique()
            ->values()
            ->toArray();
        $this->quizTags = $this->course->quizzes
            ->flatMap(fn($quiz) => $quiz->tags->pluck('name'))
            ->unique()
            ->values()
            ->toArray();



    }

    public $filteredQuizzes;

    public function searchQuizzes()
    {
        $ongoingQuizzes = collect();
        $completedQuizzes = collect();

        foreach ($this->course->quizzes as $quiz) {
            $start = $quiz->pivot?->start_at;
            $end = $quiz->pivot?->end_at;

            // Kiểm tra đã làm (ARCHIVED )
            if ($quiz->status === QuizStatus::ARCHIVED || $quiz->status === QuizStatus::CLOSED) {
                // Nếu search rỗng hoặc trùng tên quiz
                if ($this->search === '' || str_contains(strtolower($quiz->title), strtolower($this->search))) {
                    $completedQuizzes->push($quiz);
                }

                // Kiểm tra đang mở
            } elseif ($start && $end && now()->between($start, $end) && $quiz->status === QuizStatus::PUBLISHED) {
                if ($this->search === '' || str_contains(strtolower($quiz->title), strtolower($this->search))) {
                    $ongoingQuizzes->push($quiz);
                }
            }
        }


        $this->ongoingQuizzes = $ongoingQuizzes;
        $this->completedQuizzes = $completedQuizzes;

    }
    public function searchAssignments()
    {
        $ongoingAssignments = collect();
        $closedAssignments = collect();

        foreach ($this->course->assignments as $assignment) {
            $start = $assignment->pivot?->start_at;
            $end = $assignment->pivot?->end_at;
            if ($assignment->status == AssignmentStatus::DRAFT) {
                continue;
            }
            // Lọc theo search term
            if ($this->search !== '' && !str_contains(strtolower($assignment->title), strtolower($this->search))) {
                continue;
            }

            // Nếu assignment chưa Published hoặc chưa có thời gian bắt đầu → coi như closed
            if ($assignment->status !== AssignmentStatus::PUBLISHED || !$start) {
                if ($assignment->status !== AssignmentStatus::DRAFT) {
                    $closedAssignments->push($assignment);
                }

                continue;
            }

            // Ongoing: đang trong khoảng start → end
            if (now()->gte($start) && (!$end || now()->lte($end))) {
                $ongoingAssignments->push($assignment);
            } else {
                $closedAssignments->push($assignment);
            }
        }

        $this->ongoingAssignments = $ongoingAssignments;
        $this->closedAssignments = $closedAssignments;
    }

    // Thuộc tính lưu kiểu sắp xếp
    public $sortBy = 'newest';

    // Hàm đổi kiểu sắp xếp
    public function sortQuizzes(string $sort)
    {
        $this->sortBy = $sort;
        $this->filterQuizzes(); // Áp dụng lại lọc + sắp xếp
    }

    // Hàm lọc & sắp xếp quiz
    protected function filterQuizzes()
    {
        $ongoingQuizzes = collect();
        $completedQuizzes = collect();
        $now = now();

        foreach ($this->course->quizzes as $quiz) {
            $start = $quiz->pivot?->start_at;
            $end = $quiz->pivot?->end_at;


            // Quiz đã hoàn thành
            if ($quiz->status === QuizStatus::ARCHIVED || $quiz->status === QuizStatus::CLOSED) {
                $completedQuizzes->push($quiz);

                // Quiz đang mở
            } elseif (
                $start &&
                $now->gte($start) &&
                (!$end || $now->lte($end)) &&
                $quiz->status === QuizStatus::PUBLISHED
            ) {
                $ongoingQuizzes->push($quiz);
            }
        }

        // Áp dụng sắp xếp
        $this->ongoingQuizzes = $this->sortCollection($ongoingQuizzes);
        $this->completedQuizzes = $this->sortCollection($completedQuizzes);
    }
    public function sortAssignments(string $sort)
    {
        $this->sortBy = $sort;
        $this->filterAssignments();
    }

    // Hàm lọc & sắp xếp assignment
    protected function filterAssignments()
    {

        $ongoingAssignments = collect();
        $closedAssignments = collect();
        $now = now();

        foreach ($this->course->assignments as $assignment) {
            $start = $assignment->pivot?->start_at;
            $end = $assignment->pivot?->end_at;

            if ($assignment->status == AssignmentStatus::DRAFT) {
                continue;
            }
            if ($assignment->status !== AssignmentStatus::PUBLISHED || !$start) {
                if ($assignment->status !== AssignmentStatus::DRAFT) {
                    $closedAssignments->push($assignment);
                }
                continue;
            }



            if ($now->gte($start) && (!$end || $now->lte($end))) {
                $ongoingAssignments->push($assignment);
            } else {
                $closedAssignments->push($assignment);
            }
        }

        // Áp dụng sắp xếp
        $this->ongoingAssignments = $this->sortCollection($ongoingAssignments);
        $this->closedAssignments = $this->sortCollection($closedAssignments);
    }

    // Hàm sắp xếp collection dựa theo sortBy
    protected function sortCollection($collection)
    {
        return match ($this->sortBy) {
            'newest' => $collection->sortByDesc('created_at')->values(),
            'oldest' => $collection->sortBy('created_at')->values(),
            'end_at' => $collection->sortBy(fn($q) => $q->pivot?->end_at ?? now())->values(),
            default => $collection,
        };
    }
    public $selectedTag;

    public function filterQuizzesByTag($tag)
    {
        $now = now();
        $ongoingQuizzes = collect();
        $completedQuizzes = collect();

        foreach ($this->course->quizzes as $quiz) {
            // Kiểm tra pivot tồn tại
            $this->selectedTag = $tag; // Lưu tag đã chọn

            $start = $quiz->pivot?->start_at;
            $end = $quiz->pivot?->end_at;

            // Kiểm tra tag
            $hasSelectedTag = $this->selectedTag
                ? $quiz->tags->contains('name', $this->selectedTag)
                : true;

            if (!$hasSelectedTag) {
                continue;
            }

            // Lọc theo trạng thái và thời gian
            if ($quiz->status === QuizStatus::ARCHIVED || $quiz->status === QuizStatus::CLOSED) {
                $completedQuizzes->push($quiz);
            } elseif (
                $start &&
                $now->gte($start) &&
                (!$end || $now->lte($end)) && $quiz->status === QuizStatus::PUBLISHED
            ) {
                $ongoingQuizzes->push($quiz);
            }
        }

        $this->ongoingQuizzes = $ongoingQuizzes;
        $this->completedQuizzes = $completedQuizzes;
    }
    public function filterAssignmentsByTag($tag)
    {
        $ongoingAssignments = collect();
        $closedAssignments = collect();

        foreach ($this->course->assignments as $assignment) {
            $this->selectedTag = $tag;
            $start = $assignment->pivot?->start_at;
            $end = $assignment->pivot?->end_at;


            $hasSelectedTag = $this->selectedTag
                ? $assignment->tags->contains('name', $this->selectedTag)
                : true;

            if (!$hasSelectedTag) {
                continue;
            }


            if ($assignment->status !== AssignmentStatus::PUBLISHED || !$start) {
                if ($assignment->status !== AssignmentStatus::DRAFT) {
                    $closedAssignments->push($assignment);
                }
                continue;
            }


            if (now()->gte($start) && (!$end || now()->lte($end))) {
                $ongoingAssignments->push($assignment);
            } else {
                $closedAssignments->push($assignment);
            }
        }

        $this->ongoingAssignments = $ongoingAssignments;
        $this->closedAssignments = $closedAssignments;
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
