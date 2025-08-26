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
            $user = auth()->user();
            if (!$user->hasRole('student')) {
                if (in_array($quiz->status, [QuizStatus::DRAFT, QuizStatus::ARCHIVED, QuizStatus::CLOSED])) {
                    $completedQuizzes->push($quiz);
                }
            } else {
                if (in_array($quiz->status, [QuizStatus::ARCHIVED, QuizStatus::CLOSED])) {
                    $hasAttempted = $quiz->attempts()
                        ->where('student_id', $user->id)
                        ->exists();
                    if ($hasAttempted) {
                        $completedQuizzes->push($quiz);
                    } else {
                        $completedQuizzes->push($quiz);
                    }
                }
            }

            // Ongoing
            if ($quiz->status === QuizStatus::PUBLISHED && $start) {

                if (!$end || $now->between($start, $end)) {
                    $ongoingQuizzes->push($quiz);
                }
            }
        }

        // Assignments
        $user = auth()->user();

        $ongoingAssignments = collect();
        $closedAssignments = collect();

        foreach ($this->course->assignments as $assignment) {
            $start = $assignment->pivot?->start_at;
            $end = $assignment->pivot?->end_at;
            if (!$start) {
                continue;
            }
            if ($assignment->status === AssignmentStatus::DRAFT && $user->hasRole('student')) {
                continue;
            }


            if ($assignment->status === AssignmentStatus::PUBLISHED && $start && (!$end || $now->between($start, $end))) {
                $ongoingAssignments->push($assignment);
                continue;
            }


            if ($user->hasRole('student')) {
                $hasSubmitted = $assignment->submissions()->where('student_id', $user->id)->exists();
                if ($hasSubmitted || ($end && $now->gt($end)) || (!$end && $now->gte($start))) {
                    $closedAssignments->push($assignment);
                }
            } else {
                $closedAssignments->push($assignment);
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
        $now = now();

        foreach ($this->course->quizzes as $quiz) {
            $start = $quiz->pivot?->start_at;
            $end = $quiz->pivot?->end_at;
            $user = auth()->user();
            if ($this->search !== '' && !str_contains(strtolower($quiz->title), strtolower($this->search))) {
                continue;
            }
            if ($user->hasRole('student')) {
                // Student → chỉ ARCHIVED / CLOSED
                if (in_array($quiz->status, [QuizStatus::ARCHIVED, QuizStatus::CLOSED])) {
                    $hasAttempted = $quiz->attempts()->where('student_id', $user->id)->exists();
                    if ($hasAttempted) {
                        $completedQuizzes->push($quiz);
                    } else {
                        $completedQuizzes->push($quiz);
                    }
                }
            } else {
                // Teacher/Admin → DRAFT, ARCHIVED, CLOSED
                if (in_array($quiz->status, [QuizStatus::DRAFT, QuizStatus::ARCHIVED, QuizStatus::CLOSED])) {
                    $completedQuizzes->push($quiz);
                }
            }
            if ($quiz->status === QuizStatus::PUBLISHED && $start && ($end ? $now->between($start, $end) : $now->gte($start))) {
                $ongoingQuizzes->push($quiz);
            }



        }


        $this->ongoingQuizzes = $ongoingQuizzes;
        $this->completedQuizzes = $completedQuizzes;

    }
    public function searchAssignments()
    {
        $ongoingAssignments = collect();
        $closedAssignments = collect();

        $now = now();

        foreach ($this->course->assignments as $assignment) {
            $start = $assignment->pivot?->start_at;
            $end = $assignment->pivot?->end_at;

            // Bỏ qua draft cho student
            if ($assignment->status === AssignmentStatus::DRAFT && auth()->user()->hasRole('student')) {
                continue;
            }

            // Lọc theo search term
            if ($this->search !== '' && !str_contains(strtolower($assignment->title), strtolower($this->search))) {
                continue;
            }

            // Nếu chưa Published hoặc không có start → coi như closed
            if ($assignment->status !== AssignmentStatus::PUBLISHED || !$start) {
                if (!auth()->user()->hasRole('student')) {
                    $closedAssignments->push($assignment);
                } else {
                    $hasSubmitted = $assignment->submissions()->where('student_id', auth()->id())->exists();
                    if ($hasSubmitted) {
                        $closedAssignments->push($assignment);
                    } else {
                        $closedAssignments->push($assignment);
                    }
                }
                continue;
            }

            // Ongoing: đang trong khoảng start → end hoặc end null
            if ($now->gte($start) && (!$end || $now->lte($end))) {
                $ongoingAssignments->push($assignment);
            } else {
                // Qua hạn → closed
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
            $user = auth()->user();

            if (in_array($quiz->status, [QuizStatus::DRAFT, QuizStatus::ARCHIVED, QuizStatus::CLOSED])) {
                if ($user->hasRole('student')) {
                    // Student → chỉ lấy ARCHIVED / CLOSED
                    if (in_array($quiz->status, [QuizStatus::ARCHIVED, QuizStatus::CLOSED])) {
                        $hasAttempted = $quiz->attempts()->where('student_id', $user->id)->exists();
                        if ($hasAttempted) {
                            $completedQuizzes->push($quiz);
                        } else {
                            $completedQuizzes->push($quiz);
                        }

                    }
                } else {
                    // Teacher/Admin → lấy cả DRAFT / ARCHIVED / CLOSED
                    $completedQuizzes->push($quiz);
                }
            }

            // Ongoing quizzes
            if ($quiz->status === QuizStatus::PUBLISHED && $start && (!$end || $now->between($start, $end))) {
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

        $user = auth()->user();

        foreach ($this->course->assignments as $assignment) {
            $start = $assignment->pivot?->start_at;
            $end = $assignment->pivot?->end_at;

            // Nếu là DRAFT và user là student → bỏ qua
            if ($assignment->status === AssignmentStatus::DRAFT) {
                if ($user->hasRole('student')) {
                    continue;
                } else {
                    $closedAssignments->push($assignment);
                    continue;
                }
            }


            if ($assignment->status !== AssignmentStatus::PUBLISHED || !$start) {
                $closedAssignments->push($assignment);
                continue;
            }

            if ($now->gte($start) && (!$end || $now->lte($end))) {
                $ongoingAssignments->push($assignment);
            }
            if ($user->hasRole('student')) {
                $hasSubmitted = $assignment->submissions()
                    ->where('student_id', $user->id)
                    ->exists();

                if ($hasSubmitted || ($end && $now->gt($end))) {
                    $closedAssignments->push($assignment);
                }
            } else {
                $closedAssignments->push($assignment); // teacher/admin

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
        $user = auth()->user();


        $this->selectedTag = $tag;
        $now = now();
        $ongoingQuizzes = collect();
        $completedQuizzes = collect();

        foreach ($this->course->quizzes as $quiz) {
            $start = $quiz->pivot?->start_at;
            $end = $quiz->pivot?->end_at;

            // Kiểm tra tag
            if ($tag && !$quiz->tags->contains('name', $tag)) {
                continue;
            }

            // Completed quizzes
            if (in_array($quiz->status, [QuizStatus::DRAFT, QuizStatus::ARCHIVED, QuizStatus::CLOSED])) {
                if ($user->hasRole('student')) {
                    // Student chỉ lấy ARCHIVED / CLOSED
                    if (in_array($quiz->status, [QuizStatus::ARCHIVED, QuizStatus::CLOSED])) {
                        $hasAttempted = $quiz->attempts()->where('student_id', $user->id)->exists();
                        if ($hasAttempted) {
                            $completedQuizzes->push($quiz);
                        } else {
                            $completedQuizzes->push($quiz);
                        }

                    }
                } else {
                    // Teacher/Admin lấy tất cả (DRAFT, ARCHIVED, CLOSED)
                    $completedQuizzes->push(
                        $quiz,
                    );
                }

            }

            // Ongoing quizzes
            if ($quiz->status === QuizStatus::PUBLISHED && $start && (!$end || $now->between($start, $end))) {
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
        $now = now();
        $user = auth()->user();

        foreach ($this->course->assignments as $assignment) {
            $this->selectedTag = $tag;
            $start = $assignment->pivot?->start_at;
            $end = $assignment->pivot?->end_at;

            // Lọc tag
            $hasSelectedTag = $this->selectedTag
                ? $assignment->tags->contains('name', $this->selectedTag)
                : true;

            if (!$hasSelectedTag) {
                continue;
            }

            // DRAFT chỉ hiển thị cho teacher/admin
            if ($assignment->status === AssignmentStatus::DRAFT) {
                if (!$user->hasRole('student')) {
                    $closedAssignments->push($assignment);
                }
                continue;
            }

            // Nếu chưa PUBLISHED hoặc chưa có start → coi như closed
            if ($assignment->status !== AssignmentStatus::PUBLISHED || !$start) {
                $closedAssignments->push($assignment);
                continue;
            }

            // Ongoing: trong khoảng start → end hoặc end null
            if ($now->gte($start) && (!$end || $now->lte($end))) {
                $ongoingAssignments->push($assignment);
            } else {
                // Student check đã nộp chưa
                if ($user->hasRole('student')) {
                    $hasSubmitted = $assignment->submissions()
                        ->where('student_id', $user->id)
                        ->exists();

                    if ($hasSubmitted || ($end && $now->gt($end))) {
                        $closedAssignments->push($assignment);
                    }
                } else {
                    $closedAssignments->push($assignment); // teacher/admin
                }
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
