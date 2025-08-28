<?php

namespace App\Filament\Pages;

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
use Illuminate\Pagination\LengthAwarePaginator as IlluminatePaginator;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use UnitEnum;

class GradingPage extends Page
{
    use HasPageShield, WithPagination;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-pencil-square';
    protected static ?string $navigationLabel = 'Chấm điểm';
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

    // Phương thức mount(): Được gọi khi component Livewire được khởi tạo.
    public function mount(): void
    {
        $this->assignCourseColors();
    }

    // Phương thức gán màu sắc cho các khóa học.
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

    // Getter property getCourseAssignmentsProperty(): Lấy danh sách bài tập của khóa học để chấm điểm.
    public function getCourseAssignmentsProperty(): LengthAwarePaginator
    {
        $teacherCourseIds = $this->getCoursesProperty()->pluck('id');
        $query = CourseAssignment::query()
            ->whereIn('course_id', $teacherCourseIds)
            ->where(function (Builder $q) {
                $q->where('start_grading_at', '<=', now())
                    ->orWhereNull('start_grading_at');
            })
            ->with(['course', 'assignment'])
            ->orderBy('end_submission_at', 'desc');

        if ($this->search) {
            $query->whereHas('assignment', function (Builder $q) {
                $q->where('title', 'like', '%' . $this->search . '%');
            });
        }
        if ($this->courseId) {
            $query->where('course_id', $this->courseId);
        }

        $paginated = $query->paginate(10);

        $assignmentIds = $paginated->getCollection()->pluck('assignment_id')->unique()->values()->all();

        // Lấy số lượng bài nộp và bài đã chấm cho từng bài tập.
        $submittedCounts = Submission::whereIn('assignment_id', $assignmentIds)
            ->selectRaw('assignment_id, count(distinct student_id) as cnt')
            ->groupBy('assignment_id')
            ->pluck('cnt', 'assignment_id')
            ->toArray();

        $gradedCounts = Submission::whereIn('assignment_id', $assignmentIds)
            ->whereNotNull('points')
            ->selectRaw('assignment_id, count(distinct student_id) as cnt')
            ->groupBy('assignment_id')
            ->pluck('cnt', 'assignment_id')
            ->toArray();

        // Gắn số lượng vào từng đối tượng bài tập.
        $paginated->getCollection()->transform(function ($courseAssignment) use ($submittedCounts, $gradedCounts) {
            $assignment = $courseAssignment->assignment;
            if ($assignment) {
                $aid = $assignment->id;
                $assignment->submitted_students_count = $submittedCounts[$aid] ?? 0;
                $assignment->graded_students_count = $gradedCounts[$aid] ?? 0;
            } else {
                $courseAssignment->assignment = (object) [
                    'submitted_students_count' => 0,
                    'graded_students_count' => 0,
                ];
            }
            return $courseAssignment;
        });

        // Lọc kết quả dựa trên 'graded' và 'ungraded'.
        if ($this->filter === 'graded') {
            $paginated->setCollection(
                $paginated->getCollection()->filter(function ($ca) {
                    return $ca->assignment->submitted_students_count > 0 &&
                           $ca->assignment->submitted_students_count === $ca->assignment->graded_students_count;
                })
            );
        } elseif ($this->filter === 'ungraded') {
            $paginated->setCollection(
                $paginated->getCollection()->filter(function ($ca) {
                    return $ca->assignment->submitted_students_count > 0 &&
                           $ca->assignment->submitted_students_count > $ca->assignment->graded_students_count;
                })
            );
        }

        return $paginated;
    }


    // Getter property getPaginatedSubmissionsProperty(): Lấy danh sách bài nộp đã phân trang.
    public function getPaginatedSubmissionsProperty(): IlluminatePaginator
    {
        $perPage = 5;
        $items = collect($this->submissions);

        if ($this->studentSearch) {
            $search = strtolower($this->studentSearch);
            $items = $items->filter(function ($submission) use ($search) {
                return $submission->student && str_contains(strtolower($submission->student->name), $search);
            });
        }

        $currentPage = $this->getPage('submissionsPage');

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
            $this->resetPage();
        }
        if ($property === 'studentSearch') {
            $this->resetPage('submissionsPage');
        }
    }

    // Các phương thức setFilter() và setSubmissionView()
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

    // Phương thức openSubmissionsModal(): Mở modal chấm điểm và tải dữ liệu.
    public function openSubmissionsModal(string $courseAssignmentId): void
    {
        $this->selectedCourseAssignment = CourseAssignment::with(['assignment', 'course.students'])->find($courseAssignmentId);
        if (!$this->selectedCourseAssignment) {
            Notification::make()->title('Không tìm thấy bài tập!')->warning()->send();
            return;
        }
        // Kiểm tra xem đã hết hạn chấm bài chưa.
        $this->isGradingExpired = $this->selectedCourseAssignment->end_at && now()->isAfter($this->selectedCourseAssignment->end_at);

        // Lấy tất cả bài nộp cho bài tập này.
        $allSubmissions = Submission::where('assignment_id', $this->selectedCourseAssignment->assignment_id)
            ->with(['student.media', 'media'])
            ->orderBy('submitted_at', 'desc')
            ->get();

        $latestSubmissions = $allSubmissions->unique('student_id');

        $sortedSubmissions = $latestSubmissions->sortBy(function ($submission) {
            $gradedStatus = !is_null($submission->points) ? 1 : 0;
            $timestamp = optional($submission->submitted_at)->getTimestamp() ?? 0;
            return $gradedStatus . '_' . str_pad(PHP_INT_MAX - $timestamp, 20, '0', STR_PAD_LEFT);
        })->values();

        $this->submissions = $sortedSubmissions;
        $this->points = $latestSubmissions->pluck('points', 'id')->toArray();
        $this->feedback = $latestSubmissions->pluck('feedback', 'id')->toArray();

        // Lấy danh sách sinh viên chưa nộp bài.
        $submittedStudentIds = $latestSubmissions->pluck('student_id');
        $allStudentsInCourse = $this->selectedCourseAssignment->course->students;

        $this->notSubmittedStudents = $allStudentsInCourse->filter(function ($student) use ($submittedStudentIds) {
            return !$submittedStudentIds->contains($student->id);
        });

        $this->reset('studentSearch');
        $this->resetPage('submissionsPage');
        // Mở modal.
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
        if (!$submission) {
            Notification::make()->title('Lỗi')->body('Không tìm thấy bài nộp.')->danger()->send();
            return;
        }
        if (!$this->selectedCourseAssignment) {
            Notification::make()->title('Lỗi')->body('Không tìm thấy thông tin bài tập của khóa học.')->danger()->send();
            return;
        }

        // Kiểm tra quyền của người dùng.
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

        // Kiểm tra thời gian chấm bài.
        if ($startGrading && $now->isBefore($startGrading)) {
            Notification::make()->title('Chưa đến thời gian chấm bài')->body("Thời gian chấm bài bắt đầu từ: {$startGrading->format('d/m/Y H:i')}.")->warning()->send();
            return;
        }
        if ($endGrading && $now->isAfter($endGrading)) {
            Notification::make()->title('Đã hết hạn chấm bài')->body("Thời gian chấm bài đã kết thúc vào: {$endGrading->format('d/m/Y H:i')}.")->danger()->send();
            return;
        }

        // Lấy điểm và feedback.
        $points = $this->points[$submissionId] ?? null;
        $feedback = $this->feedback[$submissionId] ?? '';
        $maxPoints = $this->selectedCourseAssignment->assignment->max_points;

        // Kiểm tra tính hợp lệ của điểm.
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

        // Cập nhật bản ghi bài nộp trong database.
        $submission->update([
            'points' => $points,
            'feedback' => $feedback,
            'graded_by' => $user->id,
            'graded_at' => now(),
            'status' => $newStatus,
        ]);

        Notification::make()->title('Thành công')->body("Đã cập nhật điểm cho {$submission->student->name}.")->success()->send();
    }

    // Phương thức downloadSubmission(): Tải xuống file bài nộp.
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
