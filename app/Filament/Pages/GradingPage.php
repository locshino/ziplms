<?php

namespace App\Filament\Pages;

use App\Enums\Status\SubmissionStatus;
use App\Events\AssignmentGraded;
use App\Libs\Roles\RoleHelper;
use App\Models\CourseAssignment;
use App\Models\Submission;
use BackedEnum;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator as IlluminatePaginator;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class GradingPage extends Page
{
    use HasPageShield, WithPagination;

    // Cấu hình cho navigation menu trong Filament.
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-pencil-square';

    public static function getNavigationLabel(): string
    {
        return __('pages.grading_page');
    }

    public function getTitle(): string
    {
        return __('pages.grading_page');
    }

    protected string $view = 'filament.pages.grading-page';

    protected static ?string $slug = 'grading';

    protected static ?string $title = 'Chấm điểm bài tập';

    // Các thuộc tính public để lưu trữ trạng thái của trang.
    public string $search = '';

    public string $filter = 'all';

    public string $courseId = '';

    public bool $showInstructionsModal = false;

    public bool $showDocumentsModal = false;

    public ?CourseAssignment $selectedCourseAssignment = null;

    public $submissions = [];

    public $assignmentDocuments = [];

    public $notSubmittedStudents = [];

    public string $submissionView = 'submitted';

    public array $points = [];

    public array $feedback = [];

    public array $courseColors = [];

    public string $studentSearch = '';

    public bool $isGradingExpired = false;

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

    // Getter property getCoursesProperty(): Lấy danh sách các khóa học mà giáo viên đang dạy.
    public function getCoursesProperty()
    {
        return Auth::user()->teachingCourses()->whereHas('assignments')->orderBy('title')->get();
    }

    // Getter property getCourseAssignmentsProperty(): Lấy danh sách bài tập để chấm điểm.
    public function getCourseAssignmentsProperty(): LengthAwarePaginator
    {
        $teacherCourseIds = $this->getCoursesProperty()->pluck('id');
        $query = CourseAssignment::query()
            ->whereIn('course_id', $teacherCourseIds)
            ->where('start_grading_at', '<=', now())
            ->with(['course', 'assignment'])
            ->orderBy('end_submission_at', 'desc');

        // Áp dụng bộ lọc tìm kiếm.
        if ($this->search) {
            $query->whereHas('assignment', function (Builder $q) {
                $q->where('title', 'like', '%'.$this->search.'%');
            });
        }

        // Áp dụng bộ lọc khóa học.
        if ($this->courseId) {
            $query->where('course_id', $this->courseId);
        }

        if ($this->filter === 'graded' || $this->filter === 'ungraded') {
            $query->whereHas('assignment', function (Builder $assignmentQuery) {
                $assignmentQuery
                    ->whereHas('submissions')
                    ->where(function (Builder $subQuery) {
                        // Sử dụng subquery trong whereRaw để so sánh số lượng bài nộp và bài đã chấm.
                        $submittedCountSql = '(SELECT COUNT(DISTINCT student_id) FROM submissions WHERE submissions.assignment_id = assignments.id)';
                        $gradedCountSql = '(SELECT COUNT(DISTINCT student_id) FROM submissions WHERE submissions.assignment_id = assignments.id AND points IS NOT NULL)';

                        if ($this->filter === 'graded') {
                            $subQuery->whereRaw("$submittedCountSql = $gradedCountSql");
                        } elseif ($this->filter === 'ungraded') {
                            $subQuery->whereRaw("$submittedCountSql > $gradedCountSql");
                        }
                    });
            });
        }

        $paginated = $query->paginate(10);

        $assignmentIds = $paginated->getCollection()->pluck('assignment_id')->unique()->values()->all();

        // Đếm số sinh viên đã nộp bài cho mỗi bài tập.
        $submittedCounts = Submission::whereIn('assignment_id', $assignmentIds)
            ->selectRaw('assignment_id, count(distinct student_id) as cnt')
            ->groupBy('assignment_id')
            ->pluck('cnt', 'assignment_id')
            ->toArray();

        // Đếm số sinh viên đã được chấm điểm cho mỗi bài tập.
        $gradedCounts = Submission::whereIn('assignment_id', $assignmentIds)
            ->whereNotNull('points')
            ->selectRaw('assignment_id, count(distinct student_id) as cnt')
            ->groupBy('assignment_id')
            ->pluck('cnt', 'assignment_id')
            ->toArray();

        // Gắn số liệu thống kê vào từng đối tượng bài tập.
        $paginated->getCollection()->transform(function ($courseAssignment) use ($submittedCounts, $gradedCounts) {
            if ($assignment = $courseAssignment->assignment) {
                $aid = $assignment->id;
                $assignment->submitted_students_count = $submittedCounts[$aid] ?? 0;
                $assignment->graded_students_count = $gradedCounts[$aid] ?? 0;
            }

            return $courseAssignment;
        });

        return $paginated;
    }

    // Getter property getPaginatedSubmissionsProperty(): Lấy danh sách bài nộp đã phân trang cho modal chấm điểm.
    public function getPaginatedSubmissionsProperty(): IlluminatePaginator
    {
        $perPage = 5;
        $items = collect($this->submissions);

        // Lọc sinh viên theo tên nếu có tìm kiếm.
        if ($this->studentSearch) {
            $search = strtolower($this->studentSearch);
            $items = $items->filter(function ($submission) use ($search) {
                return $submission->student && str_contains(strtolower($submission->student->name), $search);
            });
        }

        $currentPage = $this->getPage('submissionsPage');

        // Tạo đối tượng Paginator thủ công từ collection.
        return new IlluminatePaginator(
            $items->forPage($currentPage, $perPage),
            $items->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'pageName' => 'submissionsPage']
        );
    }

    // Getter property getFilteredNotSubmittedStudentsProperty(): Lấy danh sách sinh viên chưa nộp bài đã lọc.
    public function getFilteredNotSubmittedStudentsProperty()
    {
        $students = collect($this->notSubmittedStudents);
        // Lọc sinh viên theo tên nếu có tìm kiếm.
        if ($this->studentSearch) {
            $search = strtolower($this->studentSearch);
            $students = $students->filter(function ($student) use ($search) {
                return str_contains(strtolower($student->name), $search);
            });
        }

        return $students;
    }

    // Phương thức updated(): Được gọi khi một thuộc tính public được cập nhật.
    public function updated($property): void
    {
        if (in_array($property, ['courseId', 'search', 'filter'])) {
            $this->resetPage(); // Reset trang chính.
        }
        if ($property === 'studentSearch') {
            $this->resetPage('submissionsPage'); // Reset trang trong modal.
        }
    }

    // Các phương thức set...(): Cập nhật trạng thái và reset trang khi cần.
    public function setFilter(string $filter): void
    {
        $this->filter = $filter;
        $this->resetPage();
    }

    public function setSubmissionView(string $view): void
    {
        $this->submissionView = $view;
    }

    // Các phương thức open/closeModal: Quản lý trạng thái hiển thị của các modal.
    public function openInstructionsModal(string $courseAssignmentId): void
    {
        $this->selectedCourseAssignment = CourseAssignment::with(['assignment'])->find($courseAssignmentId);
        if ($this->selectedCourseAssignment) {
            $this->showInstructionsModal = true;
        }
    }

    public function closeInstructionsModal(): void
    {
        $this->showInstructionsModal = false;
        $this->selectedCourseAssignment = null;
    }

    public function openDocumentsModal(string $courseAssignmentId): void
    {
        $this->selectedCourseAssignment = CourseAssignment::with(['assignment.media'])->find($courseAssignmentId);
        if ($this->selectedCourseAssignment) {
            $this->assignmentDocuments = $this->selectedCourseAssignment->assignment->getMedia('assignment_documents');
            $this->showDocumentsModal = true;
        }
    }

    public function closeDocumentsModal(): void
    {
        $this->showDocumentsModal = false;
        $this->selectedCourseAssignment = null;
        $this->assignmentDocuments = [];
    }

    // Phương thức openSubmissionsModal(): Mở modal chấm điểm và tải dữ liệu cần thiết.
    public function openSubmissionsModal(string $courseAssignmentId): void
    {
        $this->selectedCourseAssignment = CourseAssignment::with(['assignment', 'course.students'])->find($courseAssignmentId);
        if (! $this->selectedCourseAssignment) {
            Notification::make()->title('Không tìm thấy bài tập!')->warning()->send();

            return;
        }

        // Kiểm tra xem đã hết hạn chấm bài chưa.
        $this->isGradingExpired = $this->selectedCourseAssignment->end_at && now()->isAfter($this->selectedCourseAssignment->end_at);

        // SỬA LỖI 1: Sử dụng '=' thay vì '>=' để chỉ lấy bài nộp của đúng bài tập đang chọn.
        $allSubmissions = Submission::where('assignment_id', $this->selectedCourseAssignment->assignment_id)
            ->with(['student.media', 'media'])
            ->orderBy('submitted_at', 'desc')
            ->get();

        // Lấy bài nộp mới nhất của mỗi sinh viên.
        $latestSubmissions = $allSubmissions->unique('student_id');

        // Sắp xếp danh sách bài nộp: ưu tiên bài chưa chấm lên đầu, sau đó theo thời gian nộp mới nhất.
        $sortedSubmissions = $latestSubmissions->sortBy(function ($submission) {
            $gradedStatus = ! is_null($submission->points) ? 1 : 0;
            $timestamp = optional($submission->submitted_at)->getTimestamp() ?? 0;

            return $gradedStatus.'_'.str_pad(PHP_INT_MAX - $timestamp, 20, '0', STR_PAD_LEFT);
        })->values();

        // Gán dữ liệu cho các thuộc tính của component.
        $this->submissions = $sortedSubmissions;
        $this->points = $latestSubmissions->pluck('points', 'id')->toArray();
        $this->feedback = $latestSubmissions->pluck('feedback', 'id')->toArray();

        // Lấy danh sách sinh viên chưa nộp bài.
        $submittedStudentIds = $latestSubmissions->pluck('student_id');
        $allStudentsInCourse = $this->selectedCourseAssignment->course->students;
        $this->notSubmittedStudents = $allStudentsInCourse->reject(function ($student) use ($submittedStudentIds) {
            return $submittedStudentIds->contains($student->id);
        });

        $this->reset('studentSearch');
        $this->resetPage('submissionsPage');
        $this->dispatch('open-modal', id: 'submissions-modal');
    }

    // Phương thức closeSubmissionsModal(): Đóng modal chấm điểm và reset trạng thái.
    public function closeSubmissionsModal(): void
    {
        $this->selectedCourseAssignment = null;
        $this->submissions = [];
        $this->reset('points', 'feedback', 'submissionView', 'notSubmittedStudents', 'studentSearch', 'isGradingExpired');
    }

    // Phương thức saveGrade(): Lưu điểm và phản hồi của bài nộp.
    public function saveGrade(string $submissionId): void
    {
        $submission = Submission::find($submissionId);
        if (! $submission || ! $this->selectedCourseAssignment) {
            Notification::make()->title('Lỗi')->body('Không tìm thấy bài nộp hoặc bài tập.')->danger()->send();

            return;
        }

        // Kiểm tra quyền của người dùng (phải là giáo viên của khóa học hoặc admin).
        $course = $this->selectedCourseAssignment->course;
        $user = Auth::user();
        $isCourseTeacher = $user->id === $course->teacher_id;
        $isPrivilegedUser = RoleHelper::isAdministrative($user);

        if (! $isCourseTeacher && ! $isPrivilegedUser) {
            Notification::make()->title('Không được phép')->body('Bạn không có quyền chấm bài cho khóa học này.')->danger()->send();

            return;
        }

        // Kiểm tra hạn chấm bài.
        $endGrading = $this->selectedCourseAssignment->end_at;
        if ($endGrading && now()->isAfter($endGrading)) {
            Notification::make()->title('Đã hết hạn chấm bài')->body("Thời gian chấm bài đã kết thúc vào: {$endGrading->format('d/m/Y H:i')}.")->danger()->send();

            return;
        }

        // Lấy điểm và phản hồi từ input.
        $points = $this->points[$submissionId] ?? null;
        $feedback = $this->feedback[$submissionId] ?? '';
        $maxPoints = $this->selectedCourseAssignment->assignment->max_points;

        // Kiểm tra tính hợp lệ của điểm.
        if ($points !== null && (! is_numeric($points) || $points < 0 || $points > $maxPoints)) {
            Notification::make()->title('Dữ liệu không hợp lệ')->body("Điểm phải là một số từ 0 đến {$maxPoints}.")->danger()->send();

            return;
        }

        // Cập nhật trạng thái bài nộp. Mặc định là GRADED.
        $newStatus = SubmissionStatus::GRADED;
        if ($points === null) {
            $endAt = $this->selectedCourseAssignment->end_submission_at;
            $isLate = $endAt ? $submission->submitted_at->isAfter($endAt) : false;
            $newStatus = $isLate ? SubmissionStatus::LATE : SubmissionStatus::SUBMITTED;
        }

        // Cập nhật bản ghi bài nộp trong database.
        $submission->update([
            'points' => $points,
            'feedback' => $feedback,
            'graded_by' => $user->id,
            'graded_at' => now(),
            'status' => $newStatus,
        ]);

        Notification::make()->title('Thành công')->body("Đã cập nhật điểm cho {$submission->student->name}.")->success()->send();

        // Kích hoạt sự kiện AssignmentGraded nếu đã cho điểm.
        if ($points !== null) {
            event(new AssignmentGraded($submission));
        }
    }

    // Phương thức downloadSubmission(): Tải xuống file bài nộp.
    public function downloadSubmission(string $submissionId)
    {
        $submission = Submission::with('media')->find($submissionId);
        $mediaItem = $submission?->getFirstMedia('submission_documents');

        if (! $mediaItem) {
            Notification::make()->title('Lỗi')->body('Không tìm thấy tệp đính kèm.')->danger()->send();

            return null;
        }

        return response()->download($mediaItem->getPath(), $mediaItem->file_name);
    }
}
