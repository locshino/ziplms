<?php

namespace App\Filament\Pages;

use App\Libs\Roles\RoleHelper;
use App\Models\CourseAssignment;
use App\Models\Submission;
use BackedEnum;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use UnitEnum;

class GradingPage extends Page
{
    use WithPagination, HasPageShield;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-pencil-square';
    protected static ?string $navigationLabel = 'Chấm điểm';
    protected static UnitEnum|string|null $navigationGroup = 'Teacher Section';
    protected string $view = 'filament.pages.grading-page';
    protected static ?string $slug = 'grading';
    protected static ?string $title = 'Chấm điểm bài tập';

    public string $search = '';
    public string $filter = 'all';
    public string $courseId = '';
    public bool $showSubmissionsModal = false;
    public bool $showInstructionsModal = false;
    public ?CourseAssignment $selectedCourseAssignment = null;
    public $submissions = [];

    public array $points = [];
    public array $feedback = [];

    // Thêm thuộc tính này để lưu trữ màu sắc của khóa học
    public array $courseColors = [];

    // Định nghĩa một bảng màu
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
    public function getCoursesProperty()
    {
        return Auth::user()->taughtCourses()->whereHas('courseAssignments')->orderBy('title')->get();
    }
    public function getCourseAssignmentsProperty(): LengthAwarePaginator
    {
        $teacherCourseIds = $this->getCoursesProperty()->pluck('id');

        $query = CourseAssignment::query()
            ->whereIn('course_id', $teacherCourseIds)
            ->with(['course', 'assignment' => function ($assignmentQuery) {
                $assignmentQuery->withCount([
                    'submissions',
                    'submissions as graded_submissions_count' => function (Builder $query) {
                        $query->whereNotNull('points');
                    }
                ]);
            }]);

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

        // Sắp xếp theo hạn nộp giảm dần
        $query->orderBy('end_submission_at', 'desc');

        return $query->paginate(10);
    }

    public function updated($property): void
    {
        if (in_array($property, ['courseId', 'search', 'filter'])) {
            $this->resetPage();
        }
    }

    public function setFilter(string $filter): void
    {
        $this->filter = $filter;
    }

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

    public function openSubmissionsModal(string $courseAssignmentId): void
    {
        $this->selectedCourseAssignment = CourseAssignment::with(['assignment', 'course'])->find($courseAssignmentId);
        if (!$this->selectedCourseAssignment) {
            Notification::make()->title('Không tìm thấy bài tập!')->warning()->send();
            return;
        }
        $this->submissions = Submission::where('assignment_id', $this->selectedCourseAssignment->assignment_id)
            ->with(['student', 'media'])
            ->get();
        $this->points = $this->submissions->pluck('points', 'id')->toArray();
        $this->feedback = $this->submissions->pluck('feedback', 'id')->toArray();

        $this->showSubmissionsModal = true;
    }

    public function closeSubmissionsModal(): void
    {
        $this->showSubmissionsModal = false;
        $this->selectedCourseAssignment = null;
        $this->submissions = [];
        $this->reset('points', 'feedback');
    }

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

        $course = $this->selectedCourseAssignment->course;
        $user = Auth::user();

        // 1. Điều kiện: Người dùng phải là giáo viên của khóa học HOẶC có vai trò quản trị
        $isCourseTeacher = $user->id === $course->teacher_id;
        $isPrivilegedUser = RoleHelper::isAdministrative($user);

        if (!$isCourseTeacher && !$isPrivilegedUser) {
            Notification::make()
                ->title('Không được phép')
                ->body('Bạn không có quyền chấm bài cho khóa học này.')
                ->danger()
                ->send();
            return;
        }

        $now = now();
        $startGrading = $this->selectedCourseAssignment->start_grading_at;
        $endGrading = $this->selectedCourseAssignment->end_at;

        if ($startGrading && $now->isBefore($startGrading)) {
            Notification::make()
                ->title('Chưa đến thời gian chấm bài')
                ->body("Thời gian chấm bài bắt đầu từ: {$startGrading->format('d/m/Y H:i')}.")
                ->warning()
                ->send();
            return;
        }

        if ($endGrading && $now->isAfter($endGrading)) {
            Notification::make()
                ->title('Đã hết hạn chấm bài')
                ->body("Thời gian chấm bài đã kết thúc vào: {$endGrading->format('d/m/Y H:i')}.")
                ->danger()
                ->send();
            return;
        }

        // --- KẾT THÚC THAY ĐỔI ---

        $points = $this->points[$submissionId] ?? null;
        $feedback = $this->feedback[$submissionId] ?? '';
        $maxPoints = $this->selectedCourseAssignment->assignment->max_points;

        if ($points !== null && (!is_numeric($points) || $points < 0 || $points > $maxPoints)) {
            Notification::make()->title('Dữ liệu không hợp lệ')
                        ->body("Điểm phải là một số từ 0 đến {$maxPoints}.")
                        ->danger()->send();
            return;
        }

        $submission->update([
            'points' => $points,
            'feedback' => $feedback,
            'graded_by' => $user->id,
            'graded_at' => now(),
        ]);

        Notification::make()->title('Thành công')->body("Đã cập nhật điểm cho {$submission->student->name}.")->success()->send();
    }

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
