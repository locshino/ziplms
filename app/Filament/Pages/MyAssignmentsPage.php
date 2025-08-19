<?php

namespace App\Filament\Pages;

use App\Models\Assignment;
use App\Models\Course;
use App\Models\Submission;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class MyAssignmentsPage extends Page
{
    use WithPagination, WithFileUploads, HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'Student Section';
    protected static ?string $navigationLabel = 'My Assignments';
    protected static ?string $title = 'My Assignments';
    protected static ?string $slug = 'my-assignments';
    protected static string $view = 'filament.pages.my-assignments';

    /*
    |--------------------------------------------------------------------------
    | Thuộc tính Trạng thái của Component (Component State Properties)
    |--------------------------------------------------------------------------
    |
    | Đây là các thuộc tính công khai (public) của Livewire component.
    | Chúng lưu trữ trạng thái của trang, ví dụ như từ khóa tìm kiếm,
    | bộ lọc đang được áp dụng, ID khóa học, và trạng thái hiển thị
    | của các modal (cửa sổ pop-up).
    |
    */
    public string $search = '';
    public string $filter = 'all';
    public string $courseId = '';
    public bool $showSubmissionModal = false;
    public bool $showInstructionsModal = false;
    public ?Assignment $selectedAssignment = null;

    public string $submissionType = 'file';
    public $file;
    public string $link_url = '';
    public string $notes = '';

    /*
    |--------------------------------------------------------------------------
    | Đồng bộ hóa với Query String (Query String Synchronization)
    |--------------------------------------------------------------------------
    |
    | Mảng này cho phép đồng bộ hóa các thuộc tính của component với
    | query string trên URL. Điều này giúp người dùng có thể đánh dấu
    | trang (bookmark) hoặc chia sẻ link với trạng thái tìm kiếm,
    | bộ lọc đã được áp dụng.
    |
    */
    protected $queryString = [
        'search' => ['except' => ''],
        'filter' => ['except' => 'all'],
        'courseId' => ['except' => ''],
    ];

    /*
    |--------------------------------------------------------------------------
    | Quy tắc Xác thực (Validation Rules)
    |--------------------------------------------------------------------------
    |
    | Phương thức này định nghĩa các quy tắc xác thực (validation rules)
    | cho form nộp bài. Nó đảm bảo dữ liệu người dùng nhập vào là hợp lệ
    | trước khi xử lý.
    |
    */
    protected function rules(): array
    {
        return [
            'file' => [
                Rule::requiredIf($this->submissionType === 'file'),
                'nullable',
                'file',
                'max:25600',
                'mimes:pdf,doc,docx,zip,rar,png,jpg'
            ],
            'link_url' => [
                Rule::requiredIf($this->submissionType === 'link'),
                'nullable',
                'url',
                'max:2048'
            ],
            'notes' => 'nullable|string|max:5000',
        ];
    }

    public function getCoursesProperty()
    {
        return Course::whereHas('assignments')->orderBy('title')->get();
    }

    public function getAssignmentsProperty()
    {
        $assignmentsQuery = Assignment::query()
            ->with(['submissions', 'course'])
            ->where('title', 'like', '%' . $this->search . '%')
            ->orderByRaw(
                'CASE WHEN due_at >= ? THEN 0 ELSE 1 END,
                 CASE WHEN due_at >= ? THEN due_at END ASC,
                 CASE WHEN due_at < ? THEN due_at END DESC',
                [now(), now(), now()]
            );
        if ($this->courseId) {
            $assignmentsQuery->where('course_id', $this->courseId);
        }
        match ($this->filter) {
            'submitted' => $assignmentsQuery->whereHas('submissions', fn ($q) => $q->where('student_id', Auth::id())),
            'not_submitted' => $assignmentsQuery->whereDoesntHave('submissions', fn ($q) => $q->where('student_id', Auth::id()))->where('due_at', '>', now()),
            'overdue' => $assignmentsQuery->whereDoesntHave('submissions', fn ($q) => $q->where('student_id', Auth::id()))->where('due_at', '<=', now()),
            default => null,
        };

        // Phân trang kết quả
        return $assignmentsQuery->paginate(10);
    }

    public function updatedCourseId(): void
    {
        $this->resetPage();
    }

    public function updatedSubmissionType(): void
    {
        $this->reset('file', 'link_url');
        $this->resetErrorBag();
    }

    public function setFilter(string $filter): void
    {
        $this->filter = $filter;
        $this->resetPage();
    }

    public function openInstructionsModal(string $assignmentId): void
    {
        $this->selectedAssignment = Assignment::with('course')->find($assignmentId);
        if ($this->selectedAssignment) {
            $this->showInstructionsModal = true;
        }
    }

    public function closeInstructionsModal(): void
    {
        $this->showInstructionsModal = false;
        $this->selectedAssignment = null;
    }

    public function openSubmissionModal(string $assignmentId): void
    {
        $this->selectedAssignment = Assignment::with('course')->find($assignmentId);
        if (!$this->selectedAssignment) return;

        $isSubmitted = $this->selectedAssignment->submissions()->where('student_id', Auth::id())->exists();
        $isOverdue = $this->selectedAssignment->due_at->isPast();

        if ($isSubmitted || $isOverdue) {
            return;
        }

        $this->reset('file', 'link_url', 'notes', 'submissionType');
        $this->resetErrorBag();
        $this->showSubmissionModal = true;
    }

    public function closeSubmissionModal(): void
    {
        $this->showSubmissionModal = false;
        $this->selectedAssignment = null;
    }

    /*
    |--------------------------------------------------------------------------
    | Xử lý Nộp bài (Submit Assignment)
    |--------------------------------------------------------------------------
    |
    | Phương thức này chứa logic để xử lý việc nộp bài của sinh viên.
    | Nó kiểm tra hạn nộp, xác thực dữ liệu, lưu file/link,
    | và tạo một bản ghi `Submission` mới trong cơ sở dữ liệu.
    |
    */
    public function submitAssignment(): void
    {
        if (!$this->selectedAssignment) return;

        // Kiểm tra lần cuối trước khi nộp
        if ($this->selectedAssignment->due_at->isPast()) {
            Notification::make()
                ->title('Nộp bài thất bại!')
                ->body('Hạn nộp cho bài tập này đã qua.')
                ->danger()
                ->send();
            $this->closeSubmissionModal();
            return;
        }

        $this->validate();

        $submissionContent = '';
        $filePath = null;

        if ($this->submissionType === 'file' && $this->file) {
            $filePath = $this->file->store('submissions/' . Auth::id(), 'public');
            $fileName = $this->file->getClientOriginalName();
            $submissionContent = "File submitted: '{$fileName}'.\n\nNotes:\n" . $this->notes;
        } elseif ($this->submissionType === 'link') {
            $submissionContent = "Submitted via link: {$this->link_url}\n\nNotes:\n" . $this->notes;
        }

        Submission::create([
            'assignment_id' => $this->selectedAssignment->id,
            'student_id' => Auth::id(),
            'feedback' => $submissionContent,
            'file_path' => $filePath,
            'submitted_at' => now(),
        ]);

        $this->closeSubmissionModal();

        Notification::make()
            ->title('Nộp bài thành công!')
            ->success()
            ->send();
    }
}
