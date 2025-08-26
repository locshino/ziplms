<?php

namespace App\Filament\Pages;

// Khai báo namespace và import các lớp, thư viện cần thiết cho trang.
use App\Enums\Status\SubmissionStatus;
use App\Libs\Roles\RoleHelper;
use App\Models\CourseAssignment;
use App\Models\Submission;
use App\Models\User;
use BackedEnum;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use UnitEnum;

class GradingPage extends Page
{
    use HasPageShield, WithPagination;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-pencil-square';
    protected static ?string $navigationLabel = 'Chấm điểm';
    protected static UnitEnum|string|null $navigationGroup = 'Teacher Section';
    protected string $view = 'filament.pages.grading-page';
    protected static ?string $slug = 'grading';
    protected static ?string $title = 'Chấm điểm bài tập';

    // Các thuộc tính (biến) công khai để quản lý trạng thái của trang.
    public string $search = '';
    public string $filter = 'all';
    public string $courseId = '';
    public bool $showInstructionsModal = false;
    public ?CourseAssignment $selectedCourseAssignment = null;
    public $submissions = [];
    public $notSubmittedStudents = [];
    public string $submissionView = 'submitted';
    public array $points = [];
    public array $feedback = [];
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

    // Gán màu sắc cho từng khóa học để dễ phân biệt trên giao diện.
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

    // Đồng bộ các thuộc tính filter, search với query string trên URL.
    protected function queryString(): array
    {
        return [
            'search' => ['except' => ''],
            'filter' => ['except' => 'all'],
            'courseId' => ['except' => ''],
        ];
    }

    // Lấy danh sách các khóa học mà người dùng hiện tại đang dạy.
    public function getCoursesProperty()
    {
        return Auth::user()->teachingCourses()->whereHas('assignments')->orderBy('title')->get();
    }

    // Lấy danh sách bài tập, áp dụng các bộ lọc, tìm kiếm và phân trang.
    public function getCourseAssignmentsProperty(): LengthAwarePaginator
    {
        $teacherCourseIds = $this->getCoursesProperty()->pluck('id');

        $query = CourseAssignment::query()
            ->whereIn('course_id', $teacherCourseIds)
            ->where(function (Builder $q) {
                $q->where('start_grading_at', '<=', now())
                    ->orWhereNull('start_grading_at');
            })
            ->with([
                'course',
                'assignment' => function ($assignmentQuery) {
                    $assignmentQuery->withCount([
                        'submissions as submitted_students_count' => fn(Builder $q) => $q->selectRaw('count(distinct student_id)'),
                        'submissions as graded_students_count' => fn(Builder $q) => $q->whereNotNull('points')->selectRaw('count(distinct student_id)'),
                    ]);
                }
            ]);

        if ($this->search) {
            $query->whereHas('assignment', function (Builder $q) {
                $q->where('title', 'like', '%' . $this->search . '%');
            });
        }
        if ($this->courseId) {
            $query->where('course_id', $this->courseId);
        }

        match ($this->filter) {
            'graded' => $query->whereHas('assignment', function (Builder $assignmentQuery) {
                    $assignmentQuery->whereHas('submissions', fn($q) => $q->whereNotNull('points'));
                }),
            'ungraded' => $query->where(function (Builder $mainQuery) {
                    $mainQuery->whereHas('assignment', function (Builder $assignmentQuery) {
                        $assignmentQuery->whereDoesntHave('submissions', fn($q) => $q->whereNotNull('points'));
                    });
                }),
            default => null,
        };

        $query->orderBy('end_submission_at', 'desc');

        return $query->paginate(10);
    }

    // Hook `updated` sẽ reset phân trang khi người dùng thay đổi bộ lọc.
    public function updated($property): void
    {
        if (in_array($property, ['courseId', 'search', 'filter'])) {
            $this->resetPage();
        }
    }

    // Cập nhật giá trị cho bộ lọc.
    public function setFilter(string $filter): void
    {
        $this->filter = $filter;
    }

    // Thay đổi tab đang xem trong modal chấm bài (đã nộp / chưa nộp).
    public function setSubmissionView(string $view): void
    {
        $this->submissionView = $view;
    }

    // Mở modal hiển thị hướng dẫn bài tập.
    public function openInstructionsModal(string $courseAssignmentId): void
    {
        $this->selectedCourseAssignment = CourseAssignment::with(['assignment'])->find($courseAssignmentId);
        if ($this->selectedCourseAssignment) {
            $this->showInstructionsModal = true;
        }
    }

    // Đóng modal hướng dẫn.
    public function closeInstructionsModal(): void
    {
        $this->showInstructionsModal = false;
        $this->selectedCourseAssignment = null;
    }

    // Mở modal chấm bài và tải dữ liệu cần thiết.
    public function openSubmissionsModal(string $courseAssignmentId): void
    {
        $this->selectedCourseAssignment = CourseAssignment::with(['assignment', 'course.students'])->find($courseAssignmentId);
        if (!$this->selectedCourseAssignment) {
            Notification::make()->title('Không tìm thấy bài tập!')->warning()->send();
            return;
        }

        $allSubmissions = Submission::where('assignment_id', $this->selectedCourseAssignment->assignment_id)
            ->with(['student.media', 'media'])
            ->orderBy('submitted_at', 'desc')
            ->get();
        $this->submissions = $allSubmissions->unique('student_id');
        $this->points = $this->submissions->pluck('points', 'id')->toArray();
        $this->feedback = $this->submissions->pluck('feedback', 'id')->toArray();

        $submittedStudentIds = $this->submissions->pluck('student_id');
        $allStudentsInCourse = $this->selectedCourseAssignment->course->students;

        $this->notSubmittedStudents = $allStudentsInCourse->filter(function ($student) use ($submittedStudentIds) {
            return !$submittedStudentIds->contains($student->id);
        });

        $this->dispatch('open-modal', id: 'submissions-modal');
    }

    // Đóng modal chấm bài và reset trạng thái.
    public function closeSubmissionsModal(): void
    {
        $this->selectedCourseAssignment = null;
        $this->submissions = [];
        $this->reset('points', 'feedback', 'submissionView', 'notSubmittedStudents');
    }

    // Lưu điểm và phản hồi cho một bài nộp.
    public function saveGrade(string $submissionId): void
    {
        $submission = Submission::find($submissionId);
        if (!$submission) {
            Notification::make()->title('Lỗi')->body('Không tìm thấy bài nộp.')->danger()->send();
            return;
        }
        if (!$this->selectedCourseAssignment) {
            Notification::make()->title('Lỗi')->body('Không tìm thấy thông tin bài tập của khóa học.')->danger()->send();
            return;
        }

        // Kiểm tra quyền và thời gian chấm bài.
        $course = $this->selectedCourseAssignment->course;
        $user = Auth::user();
        $isCourseTeacher = $user->id === $course->teacher_id;
        $isPrivilegedUser = RoleHelper::isAdministrative($user);

        if (!$isCourseTeacher && !$isPrivilegedUser) {
            Notification::make()->title('Không được phép')->body('Bạn không có quyền chấm bài cho khóa học này.')->danger()->send();
            return;
        }

        $now = now();
        $startGrading = $this->selectedCourseAssignment->start_grading_at;
        $endGrading = $this->selectedCourseAssignment->end_at;

        if ($startGrading && $now->isBefore($startGrading)) {
            Notification::make()->title('Chưa đến thời gian chấm bài')->body("Thời gian chấm bài bắt đầu từ: {$startGrading->format('d/m/Y H:i')}.")->warning()->send();
            return;
        }
        if ($endGrading && $now->isAfter($endGrading)) {
            Notification::make()->title('Đã hết hạn chấm bài')->body("Thời gian chấm bài đã kết thúc vào: {$endGrading->format('d/m/Y H:i')}.")->danger()->send();
            return;
        }

        // Kiểm tra dữ liệu điểm.
        $points = $this->points[$submissionId] ?? null;
        $feedback = $this->feedback[$submissionId] ?? '';
        $maxPoints = $this->selectedCourseAssignment->assignment->max_points;

        if ($points !== null && (!is_numeric($points) || $points < 0 || $points > $maxPoints)) {
            Notification::make()->title('Dữ liệu không hợp lệ')->body("Điểm phải là một số từ 0 đến {$maxPoints}.")->danger()->send();
            return;
        }

        // Cập nhật trạng thái bài nộp.
        $newStatus = SubmissionStatus::GRADED;
        if ($points === null) {
            $endAt = $this->selectedCourseAssignment->end_submission_at;
            $isLate = $endAt ? $submission->submitted_at->isAfter($endAt) : false;
            $newStatus = $isLate ? SubmissionStatus::LATE : SubmissionStatus::SUBMITTED;
        }

        // Lưu thông tin vào cơ sở dữ liệu.
        $submission->update([
            'points' => $points,
            'feedback' => $feedback,
            'graded_by' => $user->id,
            'graded_at' => now(),
            'status' => $newStatus,
        ]);

        Notification::make()->title('Thành công')->body("Đã cập nhật điểm cho {$submission->student->name}.")->success()->send();
    }

    // Xử lý việc tải xuống tệp bài nộp của sinh viên.
    public function downloadSubmission(string $submissionId)
    {
        $submission = Submission::with('media')->find($submissionId);
        $mediaItem = $submission?->getFirstMedia('submission_documents');

        if (!$mediaItem) {
            Notification::make()->title('Lỗi')->body('Không tìm thấy tệp đính kèm.')->danger()->send();
            return null;
        }

        return response()->download($mediaItem->getPath(), $mediaItem->file_name);
    }
}