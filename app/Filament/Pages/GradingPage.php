<?php

namespace App\Filament\Pages;

use App\Models\Assignment;
use App\Models\Course;
use App\Models\Submission;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;

class GradingPage extends Page
{
    use WithPagination, HasPageShield;

    // --- Page Configuration ---
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'Bài tập của học sinh';
    protected static ?string $navigationGroup = 'Quản lý Giảng dạy';
    protected static string $view = 'filament.pages.grading-page';
    protected static ?string $slug = 'grading';
    protected static ?string $title = 'Chấm điểm bài tập';

    // --- Component State ---
    public string $search = '';
    public string $filter = 'all';
    public string $courseId = '';
    public bool $showSubmissionsModal = false;
    public bool $showInstructionsModal = false;
    public ?Assignment $selectedAssignment = null;
    public $submissions = [];

    // --- Properties for Grading ---
    public array $grades = [];
    public array $feedbackNotes = [];


    protected $queryString = [
        'search' => ['except' => ''],
        'filter' => ['except' => 'all'],
        'courseId' => ['except' => ''],
    ];

    // --- Computed Properties ---
    public function getCoursesProperty()
    {
        $teacherCourseIds = Auth::user()->taughtCourses()->pluck('id');
        return Course::whereIn('id', $teacherCourseIds)
            ->whereHas('assignments')
            ->orderBy('title')
            ->get();
    }

    protected function getViewData(): array
    {
        $teacherCourseIds = Auth::user()->taughtCourses()->pluck('id');

        $assignmentsQuery = Assignment::query()
            ->whereIn('course_id', $teacherCourseIds)
            ->with(['course'])
            ->withCount(['submissions', 'submissions as graded_submissions_count' => function ($query) {
                $query->whereNotNull('grade');
            }])
            ->where('title', 'like', '%' . $this->search . '%')
            ->orderBy('due_at', 'desc');

        if ($this->courseId) {
            $assignmentsQuery->where('course_id', $this->courseId);
        }

        match ($this->filter) {
            'graded' => $assignmentsQuery->whereHas('submissions', fn($q) => $q->whereNotNull('grade')),
            'ungraded' => $assignmentsQuery->where(function ($query) {
                $query->whereDoesntHave('submissions')
                      ->orWhereHas('submissions', fn($q) => $q->whereNull('grade'));
            }),
            default => null,
        };

        return [
            'assignments' => $assignmentsQuery->paginate(10)
        ];
    }

    // --- Actions & Methods ---
    public function updatedCourseId(): void
    {
        $this->resetPage();
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

    /**
     * Mở modal xem danh sách bài nộp.
     * Cập nhật: Khởi tạo điểm và feedback từ các cột tương ứng,
     * với feedback được lấy từ cấu trúc JSON.
     */
    public function openSubmissionsModal(string $assignmentId): void
    {
        $this->selectedAssignment = Assignment::withCount(['submissions'])->find($assignmentId);
        if (!$this->selectedAssignment) {
            Notification::make()->title('Không tìm thấy bài tập!')->warning()->send();
            return;
        }

        $this->submissions = $this->selectedAssignment->submissions()->with('student')->get();
        
        // Khởi tạo giá trị điểm và feedback cho form
        $this->grades = $this->submissions->pluck('grade', 'id')->toArray();
        // Lấy feedback của giáo viên từ trong mảng feedback
        $this->feedbackNotes = $this->submissions->pluck('feedback.teacher_feedback', 'id')->toArray();

        $this->showSubmissionsModal = true;
    }

    public function closeSubmissionsModal(): void
    {
        $this->showSubmissionsModal = false;
        $this->selectedAssignment = null;
        $this->submissions = [];
        $this->reset('grades', 'feedbackNotes');
    }
    
    /**
     * Lưu điểm và phản hồi cho một bài nộp cụ thể.
     * Cập nhật: Cập nhật phản hồi của giáo viên vào trong cấu trúc JSON của cột 'feedback'
     * mà không làm mất dữ liệu gốc của sinh viên.
     */
    public function saveGrade(int $submissionId): void
    {
        $submission = Submission::find($submissionId);
        if (!$submission) {
            Notification::make()->title('Lỗi')->body('Không tìm thấy bài nộp.')->danger()->send();
            return;
        }
        
        $grade = $this->grades[$submissionId] ?? null;
        $teacherFeedback = $this->feedbackNotes[$submissionId] ?? '';

        // Validate
        if ($grade !== null && (!is_numeric($grade) || $grade < 0 || $grade > $this->selectedAssignment->max_points)) {
            Notification::make()->title('Dữ liệu không hợp lệ')
                        ->body("Điểm phải là một số từ 0 đến {$this->selectedAssignment->max_points}.")
                        ->danger()->send();
            return;
        }

        // Lấy dữ liệu feedback hiện tại (là một mảng), cập nhật và lưu lại
        $feedbackData = $submission->feedback ?? [];
        $feedbackData['teacher_feedback'] = $teacherFeedback;

        $submission->update([
            'grade' => $grade,
            'feedback' => $feedbackData, // Lưu lại toàn bộ mảng feedback
            'graded_at' => now(),
        ]);

        Notification::make()->title('Thành công')->body("Đã cập nhật điểm cho {$submission->student->name}.")->success()->send();
    }

    public function downloadSubmission($submissionId)
    {
        $submission = Submission::find($submissionId);
        if (!$submission || !$submission->file_path) {
            Notification::make()->title('Không tìm thấy tệp!')->danger()->send();
            return null;
        }

        if (Storage::disk('public')->exists($submission->file_path)) {
            return Storage::disk('public')->download($submission->file_path);
        }

        Notification::make()->title('Không tìm thấy tệp!')->body('Tệp tin có thể đã bị xóa trên server.')->danger()->send();
        Log::warning('File not found for download. Submission ID: ' . $submissionId . ' Path: ' . $submission->file_path);
        return null;
    }
}
