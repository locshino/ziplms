<?php

namespace App\Filament\Pages;

use App\Enums\Status\AssignmentStatus;
use App\Enums\Status\SubmissionStatus;
use App\Models\CourseAssignment;
use App\Models\Submission;
use BackedEnum;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use UnitEnum;

class MyAssignmentsPage extends Page
{
    use HasPageShield, WithFileUploads, WithPagination;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-academic-cap';

    protected static UnitEnum|string|null $navigationGroup = 'Student Section';

    protected static ?string $navigationLabel = 'My Assignments';

    protected static ?string $title = 'My Assignments';

    protected static ?string $slug = 'my-assignments';

    protected string $view = 'filament.pages.my-assignments';

    public string $search = '';

    public string $filter = 'all';

    public string $courseId = '';

    public bool $showSubmissionModal = false;

    public bool $showInstructionsModal = false;

    public ?CourseAssignment $selectedCourseAssignment = null;

    public string $submissionType = 'file';

    public $file;

    public string $link_url = '';

    public string $notes = '';

    public array $courseColors = [];

    public array $colorPalette = [
        'blue' => ['bg' => 'bg-blue-100 dark:bg-blue-900', 'text' => 'text-blue-800 dark:text-blue-200'],
        'green' => ['bg' => 'bg-green-100 dark:bg-green-900', 'text' => 'text-green-800 dark:text-green-200'],
        'red' => ['bg' => 'bg-red-100 dark:bg-red-900', 'text' => 'text-red-800 dark:text-red-200'],
        'yellow' => ['bg' => 'bg-yellow-100 dark:bg-yellow-900', 'text' => 'text-yellow-800 dark:text-yellow-200'],
        'purple' => ['bg' => 'bg-purple-100 dark:bg-purple-900', 'text' => 'text-purple-800 dark:text-purple-200'],
        'indigo' => ['bg' => 'bg-indigo-100 dark:bg-indigo-900', 'text' => 'text-indigo-800 dark:text-indigo-200'],
        'pink' => ['bg' => 'bg-pink-100 dark:bg-pink-900', 'text' => 'text-pink-800 dark:text-pink-200'],
        'orange' => ['bg' => 'bg-orange-100 dark:bg-orange-900', 'text' => 'text-orange-800 dark:text-orange-200'],
    ];

    public function mount(): void
    {
        $this->assignCourseColors();
    }

    public function assignCourseColors(): void
    {
        $courses = $this->getCoursesProperty();
        $colorKeys = array_keys($this->colorPalette);
        $colorCount = count($colorKeys);
        $i = 0;

        foreach ($courses as $course) {
            $colorKey = $colorKeys[$i % $colorCount];
            $this->courseColors[$course->id] = $this->colorPalette[$colorKey];
            $i++;
        }
    }

    protected function queryString(): array
    {
        return [
            'search' => ['except' => ''],
            'filter' => ['except' => 'all'],
            'courseId' => ['except' => ''],
        ];
    }

    protected function rules(): array
    {
        return [
            'file' => [
                Rule::requiredIf($this->submissionType === 'file'),
                'nullable',
                'file',
                'max:25600',
                'mimes:pdf,doc,docx,zip,rar,png,jpg,jpeg,txt,ppt,pptx,xls,xlsx',
            ],
            'link_url' => [
                Rule::requiredIf($this->submissionType === 'link'),
                'nullable',
                'url',
                'max:2048',
            ],
            'notes' => 'nullable|string|max:5000',
        ];
    }

    public function getCoursesProperty()
    {
        return Auth::user()->courses()->whereHas('assignments')->orderBy('title')->get();
    }

    public function getCourseAssignmentsProperty(): LengthAwarePaginator
    {
        $studentId = Auth::id();

        $query = CourseAssignment::query()
            ->whereIn('course_id', $this->getCoursesProperty()->pluck('id'))
            ->whereHas('assignment', function (Builder $q) {
                $q->where('status', AssignmentStatus::PUBLISHED);
            })
            ->with(['assignment', 'course', 'assignment.submissions' => function ($query) use ($studentId) {
                $query->where('student_id', $studentId);
            }]);

        if ($this->search) {
            $query->whereHas('assignment', function (Builder $q) {
                $q->where('title', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->courseId) {
            $query->where('course_id', $this->courseId);
        }
        match ($this->filter) {
            'submitted' => $query->whereHas(
                'assignment.submissions',
                fn (Builder $q) => $q->where('student_id', $studentId)
                    ->whereIn('status', [SubmissionStatus::SUBMITTED, SubmissionStatus::LATE])
            ),
            'graded' => $query->whereHas(
                'assignment.submissions',
                fn (Builder $q) => $q->where('student_id', $studentId)
                    ->whereIn('status', [SubmissionStatus::GRADED, SubmissionStatus::RETURNED])
            ),
            'not_submitted' => $query->whereDoesntHave('assignment.submissions', fn (Builder $q) => $q->where('student_id', $studentId))
                ->where(fn ($q) => $q->where('end_submission_at', '>', now())->orWhereNull('end_submission_at')),
            'overdue' => $query->whereDoesntHave('assignment.submissions', fn (Builder $q) => $q->where('student_id', $studentId))
                ->where('end_submission_at', '<=', now()),
            default => null,
        };

        $query->orderByRaw(
            'CASE WHEN end_submission_at >= ? OR end_submission_at IS NULL THEN 0 ELSE 1 END,
             CASE WHEN end_submission_at >= ? OR end_submission_at IS NULL THEN end_submission_at END ASC,
             CASE WHEN end_submission_at < ? THEN end_submission_at END DESC',
            [now(), now(), now()]
        );

        return $query->paginate(10);
    }

    public function updated($property): void
    {
        if (in_array($property, ['courseId', 'search', 'filter'])) {
            $this->resetPage();
        }
        if ($property === 'submissionType') {
            $this->reset('file', 'link_url');
            $this->resetErrorBag();
        }
    }

    public function setFilter(string $filter): void
    {
        $this->filter = $filter;
    }

    public function openInstructionsModal(string $courseAssignmentId): void
    {
        $this->selectedCourseAssignment = CourseAssignment::with(['assignment', 'course'])->find($courseAssignmentId);
        if ($this->selectedCourseAssignment) {
            $this->showInstructionsModal = true;
        }
    }

    public function closeInstructionsModal(): void
    {
        $this->showInstructionsModal = false;
        $this->selectedCourseAssignment = null;
    }

    public function openSubmissionModal(string $courseAssignmentId): void
    {
        $this->selectedCourseAssignment = CourseAssignment::with([
            'assignment.submissions' => fn ($q) => $q->where('student_id', Auth::id()),
            'course',
        ])->find($courseAssignmentId);

        if (! $this->selectedCourseAssignment) {
            return;
        }

        // Điều kiện: Người dùng phải được ghi danh vào khóa học
        $isEnrolled = Auth::user()->courses()->where('course_id', $this->selectedCourseAssignment->course_id)->exists();
        if (! $isEnrolled) {
            Notification::make()
                ->title('Không thể thực hiện')
                ->body('Bạn không có trong danh sách sinh viên của khóa học này.')
                ->warning()
                ->send();

            return;
        }

        // Điều kiện: Kiểm tra thời gian bắt đầu nộp bài
        $now = now();
        $startAt = $this->selectedCourseAssignment->start_at;

        // Chỉ kiểm tra thời gian bắt đầu nếu nó được thiết lập
        if ($startAt && $now->isBefore($startAt)) {
            Notification::make()
                ->title('Chưa đến thời gian làm bài')
                ->body("Bài tập này sẽ bắt đầu vào lúc: {$startAt->format('d/m/Y H:i')}.")
                ->info()
                ->send();

            return;
        }

        // Điều kiện: Đã nộp bài rồi
        if ($this->selectedCourseAssignment->assignment->submissions->isNotEmpty()) {
            Notification::make()
                ->title('Thông báo')
                ->body('Bạn đã nộp bài cho bài tập này rồi.')
                ->info()
                ->send();

            return;
        }

        // Xóa bỏ việc chặn mở modal khi hết hạn, cho phép nộp muộn
        $this->reset('file', 'link_url', 'notes', 'submissionType');
        $this->resetErrorBag();
        $this->showSubmissionModal = true;
    }

    public function closeSubmissionModal(): void
    {
        $this->showSubmissionModal = false;
        $this->selectedCourseAssignment = null;
    }

    public function submitAssignment(): void
    {
        if (! $this->selectedCourseAssignment) {
            return;
        }

        $user = Auth::user();

        // 1. Điều kiện: Người dùng phải ở trong khóa học
        $isEnrolled = $user->courses()->where('course_id', $this->selectedCourseAssignment->course_id)->exists();
        if (! $isEnrolled) {
            Notification::make()
                ->title('Nộp bài thất bại!')
                ->body('Bạn không có trong danh sách sinh viên của khóa học này.')
                ->danger()
                ->send();
            $this->closeSubmissionModal();

            return;
        }

        $now = now();
        $startAt = $this->selectedCourseAssignment->start_at;
        if ($startAt && $now->isBefore($startAt)) {
            Notification::make()
                ->title('Nộp bài thất bại!')
                ->body('Chưa đến thời gian làm bài.')
                ->warning()
                ->send();
            $this->closeSubmissionModal();

            return;
        }

        if ($this->selectedCourseAssignment->assignment->submissions()->where('student_id', $user->id)->exists()) {
            Notification::make()
                ->title('Nộp bài thất bại!')
                ->body('Bạn đã nộp bài cho bài tập này rồi.')
                ->warning()
                ->send();
            $this->closeSubmissionModal();

            return;
        }

        $this->validate();

        $endAt = $this->selectedCourseAssignment->end_submission_at;
        $isLate = $endAt ? $now->isAfter($endAt) : false;
        $status = $isLate ? SubmissionStatus::LATE : SubmissionStatus::SUBMITTED;

        $submission = Submission::create([
            'assignment_id' => $this->selectedCourseAssignment->assignment_id,
            'student_id' => $user->id,
            'content' => $this->notes,
            'status' => $status,
            'submitted_at' => now(),
        ]);

        if ($this->submissionType === 'file' && $this->file) {
            $submission->addMedia($this->file->getRealPath())
                ->usingName($this->file->getClientOriginalName())
                ->toMediaCollection('submission_documents');
        } elseif ($this->submissionType === 'link') {
            $submission->content = "Submitted via link: {$this->link_url}\n\nNotes:\n".$this->notes;
            $submission->save();
        }

        $this->closeSubmissionModal();

        Notification::make()
            ->title('Nộp bài thành công!')
            ->success()
            ->send();
    }
}
