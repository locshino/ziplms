<?php

namespace App\Filament\Pages;

use App\Enums\Status\AssignmentStatus;
use App\Enums\Status\QuizAttemptStatus;
use App\Enums\Status\QuizStatus;
use App\Libs\Roles\RoleHelper;
use App\Models\Course;
use Filament\Pages\Page;
use Illuminate\Support\Collection;

class CourseDetail extends Page
{
    // Blade view liên kết
    protected string $view = 'filament.pages.course-detail';
    // Không hiển thị navigation tự động
    protected static bool $shouldRegisterNavigation = false;
    // Course hiện tại
    public ?Course $course = null;
    // Media / tài liệu
    public $documents;
    // Quizzes
    public $ongoingQuizzes;
    public $completedQuizzes = [];
    public $completedQuizzess = [];
    // Assignments
    public $ongoingAssignments;
    public $closedAssignments;
    // Tracking user actions
    public Collection $hasAttempted;
    public Collection $hasSubmitted;
    public $isOngoingPeriod;
    public $isSubmitted;
    public $assignment;
    public $search = '';
    public $courseTags;
    public $quizTags;
    public $sortBy = 'newest';
    public $selectedTag;
    public Collection $missedQuizzes;
    public Collection $missedAssignments;

    public function mount($course = null): void
    {


        // Lấy identifier từ argument hoặc route hoặc request
        $identifier = $course ?? request()->route('course') ?? request()->get('course');

        // Nếu đã truyền model Course
        if ($identifier instanceof Course) {
            $this->course = $identifier->load(['quizzes.tags', 'quizzes.attempts', 'assignments.tags', 'assignments.submissions']);
        } else {
            // Lấy course theo id hoặc slug
            $this->course = Course::with(['quizzes.tags', 'quizzes.attempts', 'assignments.tags', 'assignments.submissions'])
                ->whereKey($identifier)
                ->orWhere('slug', $identifier)
                ->first();
        }


        if (!$this->course) {
            abort(404);
        }
        // Lấy media / documents
        $this->documents = $this->course->getMedia('course_documents');
        // Xử lý quizzes và assignments
        $this->processQuizzes();
        $this->processAssignments();
        // Lấy danh sách tags duy nhất
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

    /**
     * Xử lý quizzes: phân loại ongoing / completed, kiểm tra user đã attempt chưa.
     */
    private function processQuizzes()
    {
        $ongoingQuizzes = collect();
        $completedQuizzes = collect();
        $this->hasAttempted = collect();
        $this->isOngoingPeriod = collect();
        $this->missedQuizzes = collect(); // Khởi tạo collection

        $now = now();
        $user = auth()->user();

        $quizzes = $this->course->quizzes;

        // ... (phần code lọc theo search và tag đã có)
        if ($this->search) {
            $quizzes = $quizzes->filter(fn($quiz) => str_contains(strtolower($quiz->title), strtolower($this->search)));
        }

        // Filter by tag
        if ($this->selectedTag) {
            $tag = $this->selectedTag;
            $quizzes = $quizzes->filter(fn($quiz) => $quiz->tags->contains('name', $tag));
        }
        $this->hasAttempted = collect();
        $this->isOngoingPeriod = collect();
        foreach ($quizzes as $quiz) {
            $start = $quiz->pivot?->start_at;
            $end = $quiz->pivot?->end_at;
            $isPublished = $quiz->status === QuizStatus::PUBLISHED;

            $isOngoingPeriod = $isPublished && (!$start || $now->gte($start)) && (!$end || $now->lte($end));
            $hasAttempted = $quiz->attempts()->where('student_id', $user->id)->exists();

            if ($hasAttempted) {
                $this->hasAttempted->push($quiz);
            }

            // START: Logic cập nhật
            if (!$isOngoingPeriod && !$hasAttempted) {
                $this->missedQuizzes->push($quiz); // Bài đã hết hạn VÀ chưa làm -> Bỏ lỡ
            }
            // END: Logic cập nhật

            if (!$isOngoingPeriod) {
                $this->isOngoingPeriod->push($quiz);
            }

            if ($user->hasRole('student') || $user->hasRole('manager')) {
                if ($isOngoingPeriod && !$hasAttempted) {
                    $ongoingQuizzes->push($quiz);
                } else {
                    if (!$isOngoingPeriod || $hasAttempted || in_array($quiz->status, [QuizStatus::CLOSED, QuizStatus::ARCHIVED])) {
                        $completedQuizzes->push($quiz);
                    }
                }
            } else { // Teacher/Admin logic
                if ($isOngoingPeriod) {
                    $ongoingQuizzes->push($quiz);
                } else {
                    if (in_array($quiz->status, [QuizStatus::DRAFT, QuizStatus::ARCHIVED, QuizStatus::CLOSED])) {
                        $completedQuizzes->push($quiz);
                    }
                }
            }
        }

        $this->ongoingQuizzes = $this->sortCollection($ongoingQuizzes);
        $this->completedQuizzes = $this->sortCollection($completedQuizzes);
    }

    /**
     * Xử lý assignments: phân loại ongoing / closed, kiểm tra user đã submit chưa.
     */
    private function processAssignments()
    {
        $ongoingAssignments = collect();
        $closedAssignments = collect();
        $this->hasSubmitted = collect();
        $this->isSubmitted = collect(); // Tên biến này có nghĩa là "đã hết hạn"
        $this->missedAssignments = collect(); // Khởi tạo collection

        $now = now();
        $user = auth()->user();

        $assignments = $this->course->assignments;

        // Filter by search term
        if ($this->search) {
            $assignments = $assignments->filter(fn($assignment) => str_contains(strtolower($assignment->title), strtolower($this->search)));
        }

        // Filter by tag
        if ($this->selectedTag) {
            $tag = $this->selectedTag;
            $assignments = $assignments->filter(fn($assignment) => $assignment->tags->contains('name', $tag));
        }
        $this->hasSubmitted = collect();
        $this->isSubmitted = collect();
        foreach ($assignments as $assignment) {
            // ... (phần code bỏ qua DRAFT cho student)

            $start = $assignment->pivot?->start_at;
            $end = $assignment->pivot?->end_submission_at; // Sử dụng end_submission_at cho assignment
            $isPublished = $assignment->status === AssignmentStatus::PUBLISHED;
            $isOngoingPeriod = $isPublished && (!$start || $now->gte($start)) && (!$end || $now->lte($end));

            $hasSubmitted = $assignment->submissions()->where('student_id', $user->id)->exists();

            if ($hasSubmitted) {
                $this->hasSubmitted->push($assignment);
            }

            // START: Logic cập nhật
            if (!$isOngoingPeriod && !$hasSubmitted && $isPublished) {
                $this->missedAssignments->push($assignment); // Bài đã hết hạn VÀ chưa nộp -> Bỏ lỡ
            }
            // END: Logic cập nhật

            if (!$isOngoingPeriod) {
                $this->isSubmitted->push($assignment);
            }

            if ($user->hasRole('student') || $user->hasRole('manager')) {
                if ($isOngoingPeriod && !$hasSubmitted) {
                    $ongoingAssignments->push($assignment);
                } else {
                    if (!$isOngoingPeriod || $hasSubmitted || $assignment->status === AssignmentStatus::CLOSED) {
                        $closedAssignments->push($assignment);
                    }
                }
            } else { // Teacher/Admin logic

                if ($isOngoingPeriod) {
                    $ongoingAssignments->push($assignment);
                } else {
                    $closedAssignments->push($assignment);
                }
            }
        }

        $this->ongoingAssignments = $this->sortCollection($ongoingAssignments);
        $this->closedAssignments = $this->sortCollection($closedAssignments);
    }

    public function searchQuizzes()
    {
        $this->selectedTag = null; // Reset tag filter when searching
        $this->processQuizzes();
    }

    public function searchAssignments()
    {
        $this->selectedTag = null; // Reset tag filter when searching
        $this->processAssignments();
    }

    public function sortQuizzes(string $sort)
    {
        $this->sortBy = $sort;
        $this->processQuizzes();
    }

    public function sortAssignments(string $sort)
    {
        $this->sortBy = $sort;
        $this->processAssignments();
    }

    public function filterQuizzesByTag($tag)
    {
        $this->selectedTag = $tag;
        $this->search = ''; // Reset search when filtering by tag
        $this->processQuizzes();
    }

    public function filterAssignmentsByTag($tag)
    {
        $this->selectedTag = $tag;
        $this->search = ''; // Reset search when filtering by tag
        $this->processAssignments();
    }
    /**
     * Sắp xếp collection theo created_at hoặc end_at
     */
    protected function sortCollection($collection)
    {
        return match ($this->sortBy) {
            'newest' => $collection->sortByDesc('created_at')->values(),
            'oldest' => $collection->sortBy('created_at')->values(),
            'end_at' => $collection->sortBy(fn($item) => $item->pivot?->end_at ?? now())->values(),
            default => $collection,
        };
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
