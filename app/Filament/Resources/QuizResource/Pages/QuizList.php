<?php

namespace App\Filament\Resources\QuizResource\Pages;

use App\Filament\Resources\QuizResource;
use App\Models\Course;
use App\Models\Quiz;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class QuizList extends ListRecords
{
    protected static string $resource = QuizResource::class;

    protected static string $view = 'filament.resources.quiz-resource.pages.quiz-list';

    protected static ?string $title = 'Danh sách Quiz';

    protected function getTableQuery(): Builder
    {
        $user = Auth::user();

        // Nếu là super_admin, admin hoặc manager, hiển thị tất cả quiz
        if ($user->hasRole(['super_admin', 'admin', 'manager'])) {
            return Quiz::query()
                ->with(['course', 'attempts' => function ($query) {
                    $query->where('student_id', Auth::id());
                }]);
        }

        // Nếu là teacher, chỉ hiển thị quiz của các khóa học mình dạy
        if ($user->hasRole('teacher')) {
            return Quiz::query()
                ->whereHas('course', function ($query) use ($user) {
                    $query->where('teacher_id', $user->id);
                })
                ->with(['course', 'attempts']);
        }

        // Nếu là student, hiển thị quiz của các khóa học đã đăng ký
        return Quiz::query()
            ->whereHas('course.enrollments', function ($query) use ($user) {
                $query->where('student_id', $user->id);
            })
            ->with(['course', 'attempts' => function ($query) use ($user) {
                $query->where('student_id', $user->id);
            }]);
    }

    public function table(Table $table): Table
    {
        $user = Auth::user();

        return $table
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('title')
                    ->label('Tiêu đề Quiz')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('course.title')
                    ->label('Khóa học')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('max_points')
                    ->label('Điểm tối đa')
                    ->sortable(),

                TextColumn::make('time_limit_minutes')
                    ->label('Thời gian (phút)')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => $state ? $state.' phút' : 'Không giới hạn'),

                BadgeColumn::make('status')
                    ->label('Trạng thái')
                    ->getStateUsing(function (Quiz $record) {
                        $now = now();
                        if ($now < $record->start_at) {
                            return 'upcoming';
                        } elseif ($now > $record->end_at) {
                            return 'ended';
                        } else {
                            return 'active';
                        }
                    })
                    ->colors([
                        'warning' => 'upcoming',
                        'success' => 'active',
                        'danger' => 'ended',
                    ])
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'upcoming' => 'Sắp diễn ra',
                        'active' => 'Đang diễn ra',
                        'ended' => 'Đã kết thúc',
                    }),

                TextColumn::make('attempts_count')
                    ->label('Số lần làm')
                    ->getStateUsing(function (Quiz $record) use ($user) {
                        if ($user->hasRole(['admin', 'manager', 'teacher'])) {
                            return $record->attempts()->count();
                        }

                        return $record->attempts()->where('student_id', $user->id)->count();
                    })
                    ->visible(fn () => $user->hasRole(['admin', 'manager', 'teacher', 'student'])),

                BadgeColumn::make('best_score')
                    ->label('Điểm cao nhất')
                    ->getStateUsing(function (Quiz $record) use ($user) {
                        if ($user->hasRole(['admin', 'manager', 'teacher'])) {
                            return null; // Không hiển thị cho admin/teacher
                        }

                        $bestAttempt = $record->attempts()
                            ->where('student_id', $user->id)
                            ->whereNotNull('completed_at')
                            ->orderBy('score', 'desc')
                            ->first();

                        return $bestAttempt ? round($bestAttempt->score, 1).'%' : 'Chưa làm';
                    })
                    ->colors([
                        'success' => fn ($state) => str_contains($state, '%') && (float) str_replace('%', '', $state) >= 80,
                        'warning' => fn ($state) => str_contains($state, '%') && (float) str_replace('%', '', $state) >= 60,
                        'danger' => fn ($state) => str_contains($state, '%') && (float) str_replace('%', '', $state) < 60,
                        'gray' => fn ($state) => $state === 'Chưa làm',
                    ])
                    ->visible(fn () => $user->hasRole('student')),

                TextColumn::make('start_at')
                    ->label('Bắt đầu')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('end_at')
                    ->label('Kết thúc')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('course_id')
                    ->label('Khóa học')
                    ->options(function () use ($user) {
                        if ($user->hasRole(['admin', 'manager'])) {
                            return Course::all()->pluck('title', 'id');
                        } elseif ($user->hasRole('teacher')) {
                            return Course::where('teacher_id', $user->id)->pluck('title', 'id');
                        } else {
                            return Course::whereHas('enrollments', function ($query) use ($user) {
                                $query->where('student_id', $user->id);
                            })->pluck('title', 'id');
                        }
                    })
                    ->searchable(),

                SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'upcoming' => 'Sắp diễn ra',
                        'active' => 'Đang diễn ra',
                        'ended' => 'Đã kết thúc',
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (! $data['value']) {
                            return $query;
                        }

                        $now = now();

                        return match ($data['value']) {
                            'upcoming' => $query->where('start_at', '>', $now),
                            'active' => $query->where('start_at', '<=', $now)->where('end_at', '>=', $now),
                            'ended' => $query->where('end_at', '<', $now),
                            default => $query,
                        };
                    }),
            ])
            ->actions([
                // Action cho Student
                Action::make('take_quiz')
                    ->label('Làm bài')
                    ->icon('heroicon-o-play')
                    ->color('success')
                    ->url(fn (Quiz $record) => QuizResource::getUrl('take-quiz', ['record' => $record]))
                    ->visible(function (Quiz $record) use ($user) {
                        // Super admin và admin có thể làm bất kỳ quiz nào
                        if ($user->hasRole(['super_admin', 'admin'])) {
                            return true;
                        }

                        if (! $user->hasRole('student')) {
                            return false;
                        }

                        // Kiểm tra thời gian cho student
                        $now = now();
                        if ($now < $record->start_at || $now > $record->end_at) {
                            return false;
                        }

                        // Kiểm tra số lần làm tối đa cho student
                        if ($record->max_attempts) {
                            $completedAttempts = $record->attempts()
                                ->where('student_id', $user->id)
                                ->whereNotNull('completed_at')
                                ->count();

                            if ($completedAttempts >= $record->max_attempts) {
                                return false;
                            }
                        }

                        return true;
                    }),

                Action::make('continue_quiz')
                    ->label('Tiếp tục')
                    ->icon('heroicon-o-arrow-right')
                    ->color('warning')
                    ->url(fn (Quiz $record) => QuizResource::getUrl('take-quiz', ['record' => $record]))
                    ->visible(function (Quiz $record) use ($user) {
                        // Super admin và admin có thể tiếp tục quiz
                        if ($user->hasRole(['super_admin', 'admin'])) {
                            $ongoingAttempt = $record->attempts()
                                ->where('student_id', $user->id)
                                ->whereNull('completed_at')
                                ->exists();

                            return $ongoingAttempt;
                        }

                        if (! $user->hasRole('student')) {
                            return false;
                        }

                        // Kiểm tra có attempt đang làm dở không cho student
                        $ongoingAttempt = $record->attempts()
                            ->where('student_id', $user->id)
                            ->whereNull('completed_at')
                            ->exists();

                        return $ongoingAttempt && now() <= $record->end_at;
                    }),

                Action::make('view_results')
                    ->label('Xem kết quả')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->url(function (Quiz $record) use ($user) {
                        $latestAttempt = $record->attempts()
                            ->where('student_id', $user->id)
                            ->whereNotNull('completed_at')
                            ->latest('completed_at')
                            ->first();

                        return $latestAttempt ?
                            QuizResource::getUrl('quiz-result', ['record' => $record, 'attempt' => $latestAttempt]) :
                            null;
                    })
                    ->visible(function (Quiz $record) use ($user) {
                        // Super admin và admin có thể xem kết quả quiz của họ
                        if ($user->hasRole(['super_admin', 'admin', 'student'])) {
                            return $record->attempts()
                                ->where('student_id', $user->id)
                                ->whereNotNull('completed_at')
                                ->exists();
                        }

                        return false;
                    }),

                // Actions cho Teacher/Admin
                Action::make('manage_questions')
                    ->label('Quản lý câu hỏi')
                    ->icon('heroicon-o-question-mark-circle')
                    ->color('info')
                    ->url(fn (Quiz $record) => QuizResource::getUrl('manage-questions', ['record' => $record]))
                    ->visible(fn () => $user->hasRole(['super_admin', 'admin', 'manager', 'teacher'])),

                Action::make('view_attempts')
                    ->label('Xem kết quả')
                    ->icon('heroicon-o-chart-bar')
                    ->color('warning')
                    ->url(fn (Quiz $record) => QuizResource::getUrl('view-attempts', ['record' => $record]))
                    ->visible(fn () => $user->hasRole(['super_admin', 'admin', 'manager', 'teacher'])),

                Action::make('edit')
                    ->label('Chỉnh sửa')
                    ->icon('heroicon-o-pencil')
                    ->color('primary')
                    ->url(fn (Quiz $record) => QuizResource::getUrl('edit', ['record' => $record]))
                    ->visible(fn () => $user->hasRole(['super_admin', 'admin', 'manager', 'teacher'])),
            ])
            ->defaultSort('start_at', 'desc');
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make()
                ->label('Tạo Quiz mới')
                ->visible(fn () => auth()->user()?->hasRole(['super_admin', 'admin', 'manager', 'teacher']) ?? false),
        ];
    }

    public function getRecordUrl($record): ?string
    {
        $user = auth()->user();

        if (! $user) {
            return null;
        }

        // For super_admin, redirect to take-quiz page
        if ($user->hasRole('super_admin')) {
            return QuizResource::getUrl('take-quiz', ['record' => $record]);
        }

        // For students, redirect to take-quiz page instead of edit
        if ($user->hasRole('student')) {
            // Check if student can take the quiz
            $now = now();
            if ($now >= $record->start_at && $now <= $record->end_at) {
                return QuizResource::getUrl('take-quiz', ['record' => $record]);
            }

            return null; // No URL if can't take quiz
        }

        // For admin, manager, teacher - go to edit page
        if ($user->hasRole(['admin', 'manager', 'teacher'])) {
            return QuizResource::getUrl('edit', ['record' => $record]);
        }

        return null;
    }
}
