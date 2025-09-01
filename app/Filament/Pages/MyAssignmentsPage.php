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
use Symfony\Component\HttpFoundation\StreamedResponse;

// Trang này dành cho sinh viên xem và nộp bài tập.
class MyAssignmentsPage extends Page
{
    use HasPageShield, WithFileUploads, WithPagination;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'My Assignments';
    protected static ?string $title = 'My Assignments';
    protected static ?string $slug = 'my-assignments';
    protected string $view = 'filament.pages.my-assignments';

    // Các thuộc tính public để lưu trữ trạng thái của trang, được đồng bộ hóa với Livewire.
    public string $search = '';
    public string $filter = 'all';
    public string $courseId = '';
    public bool $showSubmissionModal = false;
    public bool $showInstructionsModal = false;
    public bool $showGradingResultModal = false;
    public bool $showSubmissionHistoryModal = false;
    public bool $showDocumentsModal = false;
    public ?CourseAssignment $selectedCourseAssignment = null;
    public ?Submission $selectedSubmission = null;
    public $submissionHistory = [];
    public $assignmentDocuments = [];
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

    // Phương thức mount(): Được gọi một lần khi component được khởi tạo.
    public function mount(): void
    {
        $this->assignCourseColors();
    }

    // Phương thức gán màu sắc ngẫu nhiên cho các khóa học để hiển thị.
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

    // Phương thức queryString(): Cấu hình Livewire để đồng bộ các thuộc tính với URL.
    protected function queryString(): array
    {
        return [
            'search' => ['except' => ''],
            'filter' => ['except' => 'all'],
            'courseId' => ['except' => ''],
        ];
    }

    // Phương thức rules(): Định nghĩa các quy tắc kiểm tra dữ liệu (validation) cho form nộp bài.
    protected function rules(): array
    {
        return [
            'file' => [
                Rule::requiredIf($this->submissionType === 'file'),
                'nullable',
                'file',
                'max:25600', // Kích thước tối đa 25MB.
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

    // Getter property getCoursesProperty(): Lấy danh sách các khóa học mà sinh viên đang học.
    public function getCoursesProperty()
    {
        return Auth::user()->courses()->whereHas('assignments')->orderBy('title')->get();
    }

    // Getter property getCourseAssignmentsProperty(): Lấy danh sách bài tập của khóa học với các bộ lọc và sắp xếp.
    public function getCourseAssignmentsProperty(): LengthAwarePaginator
    {
        $studentId = Auth::id();
        // Bắt đầu xây dựng truy vấn.
        $query = CourseAssignment::query()
            ->whereIn('course_id', $this->getCoursesProperty()->pluck('id')) // Chỉ lấy bài tập trong các khóa học của sinh viên.
            ->whereHas('assignment', function (Builder $q) {
                $q->where('status', AssignmentStatus::PUBLISHED); // Chỉ lấy bài tập đã được xuất bản.
            })
            // Tải sẵn thông tin cần thiết để tránh N+1 query.
            ->with(['assignment.media', 'course', 'assignment.submissions' => function ($query) use ($studentId) {
                // Tải sẵn các bài nộp của sinh viên hiện tại, sắp xếp mới nhất lên đầu.
                $query->where('student_id', $studentId)->orderBy('submitted_at', 'desc');
            }]);

        // Áp dụng bộ lọc tìm kiếm theo tiêu đề bài tập.
        if ($this->search) {
            $query->whereHas('assignment', function (Builder $q) {
                $q->where('title', 'like', '%'.$this->search.'%');
            });
        }

        // Áp dụng bộ lọc theo khóa học.
        if ($this->courseId) {
            $query->where('course_id', $this->courseId);
        }

        // Sử dụng match expression để xử lý các bộ lọc trạng thái.
        match ($this->filter) {
            // Đã nộp: Bao gồm các trạng thái SUBMITTED, LATE, và RETURNED (được trả về để sửa).
            'submitted' => $query->whereHas(
                'assignment.submissions',
                fn (Builder $q) => $q->where('student_id', $studentId)
                    ->whereIn('status', [SubmissionStatus::SUBMITTED, SubmissionStatus::LATE, SubmissionStatus::RETURNED])
            ),
            // Đã chấm: Chỉ trạng thái GRADED.
            'graded' => $query->whereHas(
                'assignment.submissions',
                fn (Builder $q) => $q->where('student_id', $studentId)
                    ->where('status', SubmissionStatus::GRADED)
            ),
            // Chưa nộp: Chưa có bài nộp nào và vẫn còn hạn.
            'not_submitted' => $query->whereDoesntHave('assignment.submissions', fn (Builder $q) => $q->where('student_id', $studentId))
                ->where(fn ($q) => $q->where('end_submission_at', '>', now())->orWhereNull('end_submission_at')),
            // Quá hạn: Chưa có bài nộp và đã hết hạn.
            'overdue' => $query->whereDoesntHave('assignment.submissions', fn (Builder $q) => $q->where('student_id', $studentId))
                ->where('end_submission_at', '<=', now()),
            default => null,
        };

        // Sắp xếp bài tập: ưu tiên các bài còn hạn lên đầu, sau đó sắp xếp theo hạn nộp gần nhất.
        $query->orderByRaw(
            'CASE WHEN end_submission_at >= ? OR end_submission_at IS NULL THEN 0 ELSE 1 END,
             CASE WHEN end_submission_at >= ? OR end_submission_at IS NULL THEN end_submission_at END ASC,
             CASE WHEN end_submission_at < ? THEN end_submission_at END DESC',
            [now(), now(), now()]
        );
        
        // Trả về kết quả đã phân trang.
        return $query->paginate(10);
    }

    // Phương thức updated(): Được gọi khi một thuộc tính public được cập nhật.
    public function updated($property): void
    {
        // Nếu các bộ lọc thay đổi, reset về trang đầu tiên.
        if (in_array($property, ['courseId', 'search', 'filter'])) {
            $this->resetPage();
        }
        // Nếu thay đổi loại nộp bài, reset các trường liên quan và xóa lỗi validation.
        if ($property === 'submissionType') {
            $this->reset('file', 'link_url');
            $this->resetErrorBag();
        }
    }

    // Phương thức setFilter(): Đặt bộ lọc và reset trang.
    public function setFilter(string $filter): void
    {
        $this->filter = $filter;
        $this->resetPage();
    }

    // Các phương thức open/closeModal: Quản lý trạng thái hiển thị của các modal.
    public function openInstructionsModal(string $courseAssignmentId): void
    {
        $this->selectedCourseAssignment = CourseAssignment::with(['assignment', 'course'])->find($courseAssignmentId);
        if ($this->selectedCourseAssignment) {
            $this->showInstructionsModal = true;
        }
    }

    public function openSubmissionModal(string $courseAssignmentId): void
    {
        // Tải thông tin bài tập và bài nộp gần nhất của sinh viên.
        $this->selectedCourseAssignment = CourseAssignment::with([
            'assignment.submissions' => fn ($q) => $q->where('student_id', Auth::id())->orderBy('submitted_at', 'desc'),
            'course',
        ])->find($courseAssignmentId);

        if (! $this->selectedCourseAssignment) return;

        // Kiểm tra sinh viên có trong khóa học không.
        $isEnrolled = Auth::user()->courses()->where('course_id', $this->selectedCourseAssignment->course_id)->exists();
        if (! $isEnrolled) {
            Notification::make()->title('Không thể thực hiện')->body('Bạn không có trong danh sách sinh viên của khóa học này.')->warning()->send();
            return;
        }

        $assignment = $this->selectedCourseAssignment->assignment;
        $lastSubmission = $assignment->submissions->first();
        
        if ($lastSubmission && $lastSubmission->status === SubmissionStatus::GRADED) {
            Notification::make()->title('Không thể nộp bài')->body('Bài tập của bạn đã được chấm điểm. Bạn không thể nộp lại.')->warning()->send();
            return;
        }

        // Kiểm tra số lần nộp bài tối đa.
        $maxAttempts = $assignment->max_attempts;
        $submissionCount = $assignment->submissions->count();
        if ($maxAttempts !== null && $maxAttempts > 0 && $submissionCount >= $maxAttempts) {
            Notification::make()->title('Hết lượt nộp bài')->body("Bạn đã sử dụng hết {$submissionCount}/{$maxAttempts} lần nộp bài.")->warning()->send();
            return;
        }
        
        // Kiểm tra thời gian bắt đầu làm bài.
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
        // Lấy bài nộp gần nhất của sinh viên cho bài tập này.
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
            // Lấy tất cả bài nộp của sinh viên cho bài tập này.
            $this->submissionHistory = Submission::where('assignment_id', $this->selectedCourseAssignment->assignment_id)
                ->where('student_id', Auth::id())
                ->with('media')
                ->orderBy('submitted_at', 'desc')
                ->get();
            $this->showSubmissionHistoryModal = true;
        }
    }

    public function openDocumentsModal(string $courseAssignmentId): void
    {
        $this->selectedCourseAssignment = CourseAssignment::with(['assignment.media'])->find($courseAssignmentId);
        if ($this->selectedCourseAssignment) {
            // Lấy tài liệu đính kèm của bài tập.
            $this->assignmentDocuments = $this->selectedCourseAssignment->assignment->getMedia('assignment_documents');
            $this->showDocumentsModal = true;
        }
    }

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

    public function closeDocumentsModal(): void
    {
        $this->showDocumentsModal = false;
        $this->selectedCourseAssignment = null;
        $this->assignmentDocuments = [];
    }

    // Phương thức submitAssignment(): Xử lý logic nộp bài.
    public function submitAssignment(): void
    {
        if (! $this->selectedCourseAssignment) return;
        $this->validate(); // Kiểm tra dữ liệu đầu vào.
        $user = Auth::user();
        if (! $this->checkSubmissionPreconditions($user)) return; // Kiểm tra các điều kiện tiên quyết.

        try {
            // Sử dụng transaction để đảm bảo tất cả các thao tác DB thành công hoặc không có gì cả.
            DB::transaction(function () use ($user) {
                $endAt = $this->selectedCourseAssignment->end_submission_at;
                // Xác định trạng thái nộp bài: đúng hạn hay muộn.
                $isLate = $endAt ? now()->isAfter($endAt) : false;
                $status = $isLate ? SubmissionStatus::LATE : SubmissionStatus::SUBMITTED;

                // Tạo một bản ghi bài nộp mới.
                $submission = Submission::create([
                    'assignment_id' => $this->selectedCourseAssignment->assignment_id,
                    'student_id' => $user->id,
                    'content' => $this->notes,
                    'status' => $status,
                    'submitted_at' => now(),
                ]);

                // Xử lý nộp bài bằng file.
                if ($this->submissionType === 'file' && $this->file) {
                    $submission->addMedia($this->file->getRealPath())
                        ->usingFileName($this->file->getClientOriginalName())
                        ->toMediaCollection('submission_documents');
                // Xử lý nộp bài bằng link.
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
            if (! ($e instanceof FileIsTooBig)) {
                report($e); // Báo cáo các lỗi khác.
            }
        }
    }

    // Phương thức kiểm tra các điều kiện tiên quyết trước khi cho phép nộp bài.
    protected function checkSubmissionPreconditions($user): bool
    {
        // Kiểm tra sinh viên có trong khóa học không.
        $isEnrolled = $user->courses()->where('course_id', $this->selectedCourseAssignment->course_id)->exists();
        if (! $isEnrolled) {
            Notification::make()->title('Nộp bài thất bại!')->body('Bạn không có trong danh sách sinh viên của khóa học này.')->danger()->send();
            $this->closeSubmissionModal();
            return false;
        }

        $assignment = $this->selectedCourseAssignment->assignment;
        $submissions = $assignment->submissions()->where('student_id', $user->id)->get();
        $lastSubmission = $submissions->sortByDesc('submitted_at')->first();

        // SỬA LỖI 1: Chỉ chặn nộp lại khi bài đã được chấm điểm (GRADED).
        if ($lastSubmission && $lastSubmission->status === SubmissionStatus::GRADED) {
            Notification::make()->title('Nộp bài thất bại!')->body('Bài tập đã được chấm điểm.')->warning()->send();
            $this->closeSubmissionModal();
            return false;
        }

        // Kiểm tra số lần nộp bài tối đa.
        $maxAttempts = $assignment->max_attempts;
        $submissionCount = $submissions->count();
        if ($maxAttempts !== null && $maxAttempts > 0 && $submissionCount >= $maxAttempts) {
            Notification::make()->title('Hết lượt nộp bài!')->body('Bạn đã hết số lần nộp bài cho phép.')->warning()->send();
            $this->closeSubmissionModal();
            return false;
        }

        // Kiểm tra thời gian bắt đầu làm bài.
        $startAt = $this->selectedCourseAssignment->start_at;
        if ($startAt && now()->isBefore($startAt)) {
            Notification::make()->title('Nộp bài thất bại!')->body('Chưa đến thời gian làm bài.')->warning()->send();
            $this->closeSubmissionModal();
            return false;
        }

        return true;
    }

    // Phương thức downloadSubmissionFile(): Tải xuống file bài nộp từ lịch sử.
    public function downloadSubmissionFile(string $submissionId): ?StreamedResponse
    {
        $submission = Submission::with('media')->find($submissionId);

        // Kiểm tra quyền truy cập: chỉ chủ nhân bài nộp mới được tải.
        if ($submission?->student_id !== Auth::id()) {
            Notification::make()->title('Lỗi')->body('Không có quyền truy cập.')->danger()->send();
            return null;
        }

        $mediaItem = $submission?->getFirstMedia('submission_documents');

        if (! $mediaItem) {
            Notification::make()->title('Lỗi')->body('Không tìm thấy tệp đính kèm.')->danger()->send();
            return null;
        }
        
        // Trả về response để tải file.
        return response()->streamDownload(fn() => fpassthru($mediaItem->stream()), $mediaItem->file_name);
    }
}

