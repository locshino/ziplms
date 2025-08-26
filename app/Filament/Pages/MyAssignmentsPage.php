<?php

namespace App\Filament\Pages;

use App\Enums\Status\AssignmentStatus;
use App\Enums\Status\SubmissionStatus;
use App\Models\CourseAssignment;
use App\Models\Submission;
use BackedEnum;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Exception;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use UnitEnum;

class MyAssignmentsPage extends Page
{
    use HasPageShield, WithFileUploads, WithPagination;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'My Assignments';
    protected static ?string $title = 'My Assignments';
    protected static ?string $slug = 'my-assignments';
    protected string $view = 'filament.pages.my-assignments';

    public string $search = '';
    public string $filter = 'all';
    public string $courseId = '';
    public bool $showSubmissionModal = false;
    public bool $showInstructionsModal = false;
    public bool $showGradingResultModal = false;
    public bool $showSubmissionHistoryModal = false;
    public ?CourseAssignment $selectedCourseAssignment = null;
    public ?Submission $selectedSubmission = null;
    public $submissionHistory = [];
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

    // Gán màu sắc cho từng khóa học để dễ phân biệt.
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

    // Đồng bộ các thuộc tính filter với query string trên URL.
    protected function queryString(): array
    {
        return [
            'search' => ['except' => ''],
            'filter' => ['except' => 'all'],
            'courseId' => ['except' => ''],
        ];
    }

    // Định nghĩa các quy tắc validation cho form nộp bài.
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

    // Lấy danh sách khóa học mà sinh viên đang tham gia.
    public function getCoursesProperty()
    {
        return Auth::user()->courses()->whereHas('assignments')->orderBy('title')->get();
    }

    // Lấy danh sách bài tập của sinh viên, có áp dụng filter, tìm kiếm và sắp xếp.
    public function getCourseAssignmentsProperty(): LengthAwarePaginator
    {
        $studentId = Auth::id();

        $query = CourseAssignment::query()
            ->whereIn('course_id', $this->getCoursesProperty()->pluck('id'))
            ->whereHas('assignment', function (Builder $q) {
                $q->where('status', AssignmentStatus::PUBLISHED);
            })
            ->with(['assignment', 'course', 'assignment.submissions' => function ($query) use ($studentId) {
                $query->where('student_id', $studentId)->orderBy('submitted_at', 'desc');
            }]);

        if ($this->search) {
            $query->whereHas('assignment', function (Builder $q) {
                $q->where('title', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->courseId) {
            $query->where('course_id', $this->courseId);
        }
        
        // Logic filter bài tập theo trạng thái.
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

        // Sắp xếp bài tập: ưu tiên bài chưa hết hạn lên trước, sau đó sắp xếp theo hạn nộp.
        $query->orderByRaw(
            'CASE WHEN end_submission_at >= ? OR end_submission_at IS NULL THEN 0 ELSE 1 END,
             CASE WHEN end_submission_at >= ? OR end_submission_at IS NULL THEN end_submission_at END ASC,
             CASE WHEN end_submission_at < ? THEN end_submission_at END DESC',
            [now(), now(), now()]
        );

        return $query->paginate(10);
    }

    // Hook `updated` xử lý khi một thuộc tính thay đổi (reset page, reset form...).
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

    // Cập nhật giá trị cho bộ lọc.
    public function setFilter(string $filter): void
    {
        $this->filter = $filter;
    }

    // Các phương thức để MỞ các modal.
    public function openInstructionsModal(string $courseAssignmentId): void
    {
        $this->selectedCourseAssignment = CourseAssignment::with(['assignment', 'course'])->find($courseAssignmentId);
        if ($this->selectedCourseAssignment) {
            $this->showInstructionsModal = true;
        }
    }

    public function openSubmissionModal(string $courseAssignmentId): void
    {
        $this->selectedCourseAssignment = CourseAssignment::with([
            'assignment.submissions' => fn ($q) => $q->where('student_id', Auth::id())->orderBy('submitted_at', 'desc'),
            'course',
        ])->find($courseAssignmentId);

        if (! $this->selectedCourseAssignment) {
            return;
        }

        // Kiểm tra các điều kiện trước khi cho phép mở form nộp bài.
        $isEnrolled = Auth::user()->courses()->where('course_id', $this->selectedCourseAssignment->course_id)->exists();
        if (! $isEnrolled) {
            Notification::make()->title('Không thể thực hiện')->body('Bạn không có trong danh sách sinh viên của khóa học này.')->warning()->send();
            return;
        }
        $assignment = $this->selectedCourseAssignment->assignment;
        $lastSubmission = $assignment->submissions->first();
        if ($lastSubmission && in_array($lastSubmission->status, [SubmissionStatus::GRADED, SubmissionStatus::RETURNED])) {
            Notification::make()->title('Không thể nộp bài')->body('Bài tập của bạn đã được chấm điểm. Bạn không thể nộp lại.')->warning()->send();
            return;
        }
        $maxAttempts = $assignment->max_attempts;
        $submissionCount = $assignment->submissions->count();
        if ($maxAttempts !== null && $maxAttempts > 0 && $submissionCount >= $maxAttempts) {
            Notification::make()->title('Hết lượt nộp bài')->body("Bạn đã sử dụng hết {$submissionCount}/{$maxAttempts} lần nộp bài.")->warning()->send();
            return;
        }
        $startAt = $this->selectedCourseAssignment->start_at;
        if ($startAt && now()->isBefore($startAt)) {
            Notification::make()->title('Chưa đến thời gian làm bài')->body("Bài tập này sẽ bắt đầu vào lúc: {$startAt->format('d/m/Y H:i')}.")->info()->send();
            return;
        }

        $this->reset('file', 'link_url', 'notes', 'submissionType');
        $this->resetErrorBag();
        $this->showSubmissionModal = true;
    }

    public function openGradingResultModal(string $courseAssignmentId): void
    {
        $this->selectedCourseAssignment = CourseAssignment::with(['assignment', 'course'])->find($courseAssignmentId);
        $this->selectedSubmission = Submission::where('assignment_id', $this->selectedCourseAssignment->assignment_id)
            ->where('student_id', Auth::id())
            ->with('grader')
            ->orderBy('submitted_at', 'desc')
            ->first();

        if ($this->selectedCourseAssignment && $this->selectedSubmission) {
            $this->showGradingResultModal = true;
        }
    }

    public function openSubmissionHistoryModal(string $courseAssignmentId): void
    {
        $this->selectedCourseAssignment = CourseAssignment::with(['assignment'])->find($courseAssignmentId);
        if ($this->selectedCourseAssignment) {
            $this->submissionHistory = Submission::where('assignment_id', $this->selectedCourseAssignment->assignment_id)
                ->where('student_id', Auth::id())
                ->with('media')
                ->orderBy('submitted_at', 'desc')
                ->get();
            $this->showSubmissionHistoryModal = true;
        }
    }

    // Các phương thức để ĐÓNG các modal và reset trạng thái.
    public function closeInstructionsModal(): void
    {
        $this->showInstructionsModal = false;
        $this->selectedCourseAssignment = null;
    }

    public function closeSubmissionModal(): void
    {
        $this->showSubmissionModal = false;
        $this->selectedCourseAssignment = null;
    }

    public function closeGradingResultModal(): void
    {
        $this->showGradingResultModal = false;
        $this->selectedCourseAssignment = null;
        $this->selectedSubmission = null;
    }

    public function closeSubmissionHistoryModal(): void
    {
        $this->showSubmissionHistoryModal = false;
        $this->selectedCourseAssignment = null;
        $this->submissionHistory = [];
    }

    // Xử lý logic nộp bài của sinh viên.
    public function submitAssignment(): void
    {
        if (! $this->selectedCourseAssignment) {
            return;
        }
        $this->validate();
        $user = Auth::user();
        if (! $this->checkSubmissionPreconditions($user)) {
            return;
        }
        try {
            DB::transaction(function () use ($user) {
                $endAt = $this->selectedCourseAssignment->end_submission_at;
                $isLate = $endAt ? now()->isAfter($endAt) : false;
                $status = $isLate ? SubmissionStatus::LATE : SubmissionStatus::SUBMITTED;
                $submission = Submission::create([
                    'assignment_id' => $this->selectedCourseAssignment->assignment_id,
                    'student_id' => $user->id,
                    'content' => $this->notes,
                    'status' => $status,
                    'submitted_at' => now(),
                ]);
                if ($this->submissionType === 'file' && $this->file) {
                    $submission->addMedia($this->file->getRealPath())->usingName($this->file->getClientOriginalName())->toMediaCollection('submission_documents');
                } elseif ($this->submissionType === 'link') {
                    $submission->content = "Submitted via link: {$this->link_url}\n\nNotes:\n".$this->notes;
                    $submission->save();
                }
            });
            $this->closeSubmissionModal();
            Notification::make()->title('Nộp bài thành công!')->success()->send();
        } catch (Exception $e) {
            $message = $e instanceof FileIsTooBig ? 'Tệp tải lên vượt quá dung lượng cho phép.' : 'Đã có lỗi không mong muốn xảy ra. Vui lòng thử lại.';
            Notification::make()->title('Nộp bài thất bại!')->body($message)->danger()->send();
            if (!($e instanceof FileIsTooBig)) report($e);
        }
    }

    // Hàm helper kiểm tra các điều kiện tiên quyết trước khi nộp bài.
    protected function checkSubmissionPreconditions($user): bool
    {
        $isEnrolled = $user->courses()->where('course_id', $this->selectedCourseAssignment->course_id)->exists();
        if (! $isEnrolled) {
            Notification::make()->title('Nộp bài thất bại!')->body('Bạn không có trong danh sách sinh viên của khóa học này.')->danger()->send();
            $this->closeSubmissionModal();
            return false;
        }

        $assignment = $this->selectedCourseAssignment->assignment;
        $submissions = $assignment->submissions()->where('student_id', $user->id)->get();
        $lastSubmission = $submissions->sortByDesc('submitted_at')->first();

        if ($lastSubmission && in_array($lastSubmission->status, [SubmissionStatus::GRADED, SubmissionStatus::RETURNED])) {
            Notification::make()->title('Nộp bài thất bại!')->body('Bài tập đã được chấm điểm.')->warning()->send();
            $this->closeSubmissionModal();
            return false;
        }

        $maxAttempts = $assignment->max_attempts;
        $submissionCount = $submissions->count();

        if ($maxAttempts !== null && $maxAttempts > 0 && $submissionCount >= $maxAttempts) {
            Notification::make()->title('Nộp bài thất bại!')->body('Bạn đã hết số lần nộp bài cho phép.')->warning()->send();
            $this->closeSubmissionModal();
            return false;
        }

        $startAt = $this->selectedCourseAssignment->start_at;
        if ($startAt && now()->isBefore($startAt)) {
            Notification::make()->title('Nộp bài thất bại!')->body('Chưa đến thời gian làm bài.')->warning()->send();
            $this->closeSubmissionModal();
            return false;
        }

        return true;
    }

    // Xử lý việc tải xuống tệp bài nộp.
    public function downloadSubmissionFile(string $submissionId)
    {
        $submission = Submission::with('media')->find($submissionId);

        if ($submission?->student_id !== Auth::id()) {
            Notification::make()->title('Lỗi')->body('Không có quyền truy cập.')->danger()->send();
            return null;
        }

        $mediaItem = $submission?->getFirstMedia('submission_documents');

        if (! $mediaItem) {
            Notification::make()->title('Lỗi')->body('Không tìm thấy tệp đính kèm.')->danger()->send();
            return null;
        }

        return response()->download($mediaItem->getPath(), $mediaItem->file_name);
    }
}