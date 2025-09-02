<?php

namespace App\Filament\Pages;

use App\Enums\Status\QuizAttemptStatus;
use App\Enums\Status\SubmissionStatus;
use App\Filament\Resources\QuizAttempts\Tables\QuizAttemptsTable;
use App\Filament\Resources\Submissions\Tables\SubmissionsTable;
use App\Models\Course;
use App\Models\QuizAttempt;
use App\Models\Submission;
use BackedEnum;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Carbon\Carbon; // Thêm dòng này
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification; // Thêm dòng này
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;
use pxlrbt\FilamentExcel\Actions\ExportBulkAction;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class Reports extends Page implements Tables\Contracts\HasTable
{
    use HasPageShield;
    use Tables\Concerns\InteractsWithTable;

    protected string $view = 'filament.pages.reports';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar';

    public static function getNavigationLabel(): string
    {
        return __('pages.reports');
    }

    public function getTitle(): string
    {
        return __('pages.reports');
    }

    public $courses = [];
    public $totalCourse = [];
    public $selectedCourseId = null;
    public string $activeTab = 'quizzes';
    public $startDate;
    public $endDate;
    public Collection $publishedQuizzes;
    public Collection $publishedAssignments;
    public Collection $closedQuizzes;
    public Collection $closedAssignments;
    public Collection $totalQuizzes;
    public Collection $totalAssignments;

    public function mount()
    {
        $teacherId = auth()->id();
        $this->selectedCourseId = null;
        $this->publishedQuizzes = collect();
        $this->publishedAssignments = collect();
        $this->totalQuizzes = collect();
        $this->totalAssignments = collect();

        if (Auth::user()->hasRole('teacher')) {
            $this->courses = Course::where('teacher_id', $teacherId)
                ->pluck('title', 'id')
                ->toArray();
        } else {
            $this->courses = Course::whereHas('users', function ($query) use ($teacherId) {
                $query->where('users.id', $teacherId);
            })->pluck('title', 'id')->toArray();
        }
        $this->closedQuizzes = collect();
        $this->closedAssignments = collect();
    }

    public function updated($property)
    {
        if (in_array($property, ['activeTab', 'selectedCourseId'])) {
            $this->resetTable();
        }
    }

    /**
     * Hàm xử lý khi nhấn nút lọc, có thêm validation ngày tháng.
     */
    public function applyFilters()
    {
        // Kiểm tra nếu cả hai ngày đều được chọn
        if ($this->startDate && $this->endDate) {
            $start = Carbon::parse($this->startDate);
            $end = Carbon::parse($this->endDate);

            // Nếu ngày kết thúc nhỏ hơn hoặc bằng ngày bắt đầu
            if ($end->lte($start)) {
                // Gửi thông báo lỗi
                Notification::make()
                    ->title('Lỗi lọc ngày tháng')
                    ->body('Ngày kết thúc phải lớn hơn ngày bắt đầu.')
                    ->danger()
                    ->send();

                // Dừng thực thi để không làm mới bảng
                return;
            }
        }

        // Nếu ngày tháng hợp lệ, làm mới bảng
        $this->resetTable();
    }


    public function table(Table $table): Table
    {
        if ($this->activeTab === 'quizzes') {
            return $this->quiz($table);
        }

        return $this->submission($table);
    }

    // ... các phương thức quiz() và submission() không thay đổi
    public function quiz($table)
    {
        $teacherId = auth()->id();

        if (Auth::user()->hasRole('teacher')) {

            $query = QuizAttempt::with(['quiz.courses', 'student'])
                ->whereHas('quiz.courses', function ($q) use ($teacherId) {
                    $q->where('teacher_id', $teacherId);
                });
        } else {
            $query = QuizAttempt::with(['quiz', 'student', 'quiz.courses'])
                ->whereHas('quiz.courses', function ($q) use ($teacherId) {
                    $q->whereHas('users', function ($subQuery) use ($teacherId) {
                        $subQuery->where('users.id', $teacherId);
                    });
                });
        }
        $query->when($this->selectedCourseId, function ($q) {
            $q->whereHas('quiz.courses', function ($q) {
                $q->where('courses.id', $this->selectedCourseId);
            });
        })
            ->when($this->startDate, function ($q) {
                $q->whereHas('quiz.courses', function ($q) {
                    $q->whereDate('courses.start_at', '>=', $this->startDate);
                });
            })
            ->when($this->endDate, function ($q) {
                $q->whereHas('quiz.courses', function ($q) {
                    $q->whereDate('courses.start_at', '<=', $this->endDate);
                });
            });

        return QuizAttemptsTable::configure($table)

            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('quiz.title')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('student.name')
                    ->searchable(),
                TextColumn::make('points')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('start_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('end_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('status')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultGroup('quiz.title')
            ->filters([
                SelectFilter::make('quiz.title')
                    ->label('Quiz')
                    ->searchable()
                    ->relationship('quiz', 'title'),
                SelectFilter::make('student.name')
                    ->label('Student')
                    ->searchable()
                    ->relationship('student', 'name'),
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(QuizAttemptStatus::class),
                DateRangeFilter::make('start_at'),
                DateRangeFilter::make('end_at'),
                TrashedFilter::make(),
            ])
            ->recordActions([

            ])
            ->toolbarActions([
                ExportBulkAction::make()->exports([
                    ExcelExport::make()->withColumns([
                        Column::make('id'),
                        Column::make('quiz.title'),
                        Column::make('student.name'),
                        Column::make('points'),
                        Column::make('start_at'),
                        Column::make('end_at'),
                        Column::make('status'),
                        Column::make('created_at'),
                        Column::make('updated_at'),
                    ])
                        // Optional: you can customize the filename
                        ->withFilename('quiz_point&report_' . now()),
                ]),
            ])->query(fn() => $query);
    }

    public function submission($table)
    {
        $teacherId = auth()->id();

        // Tab 'assignments'
        if (Auth::user()->hasRole('teacher')) {
            $query = Submission::with(['assignment.courses', 'student'])
                ->whereHas('assignment.courses', function ($q) use ($teacherId) {
                    $q->where('teacher_id', $teacherId);
                });
        } else {
            $query = Submission::with(['assignment', 'student', 'assignment.courses'])
                ->whereHas('assignment.courses', function ($q) use ($teacherId) {
                    $q->whereHas('users', function ($subQuery) use ($teacherId) {
                        $subQuery->where('users.id', $teacherId);
                    });
                });
        }

        $query->when($this->selectedCourseId, function ($q) {
            $q->whereHas('assignment.courses', function ($q) {
                $q->where('courses.id', $this->selectedCourseId);
            });
        })
            ->when($this->startDate, function ($q) {
                $q->whereHas('assignment.courses', function ($q) {
                    $q->whereDate('courses.start_at', '>=', $this->startDate);
                });
            })
            ->when($this->endDate, function ($q) {
                $q->whereHas('assignment.courses', function ($q) {
                    $q->whereDate('courses.start_at', '<=', $this->endDate);
                });
            });

        return SubmissionsTable::configure($table)
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('assignment.title')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('student.name')
                    ->searchable(),
                TextColumn::make('status')
                    ->searchable(),
                TextColumn::make('submitted_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('grader.name')
                    ->searchable(),
                TextColumn::make('points')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('graded_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultGroup('assignment.title')
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('assignment')
                    ->relationship('assignment', 'title')
                    ->searchable(),
                SelectFilter::make('student')
                    ->relationship('student', 'name')
                    ->searchable(),
                SelectFilter::make('grader')
                    ->relationship('grader', 'name')
                    ->searchable(),
                DateRangeFilter::make('submitted_at'),
                DateRangeFilter::make('graded_at'),
                SelectFilter::make('status')
                    ->options(SubmissionStatus::class),
            ])
            ->recordActions([

            ])
            ->toolbarActions([
                ExportBulkAction::make()
                    ->exports([
                        ExcelExport::make()->withColumns([
                            Column::make('id'),
                            Column::make('assignment.title'),
                            Column::make('student.name'),
                            Column::make('points'),
                            Column::make('status'),
                            Column::make('created_at'),
                            Column::make('updated_at'),
                        ])
                            // Optional: you can customize the filename
                            ->withFilename('assignment&report_' . now()),
                    ]),
            ])
            ->query(fn() => $query);
    }
}