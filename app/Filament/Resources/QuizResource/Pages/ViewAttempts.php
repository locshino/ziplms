<?php

namespace App\Filament\Resources\QuizResource\Pages;

use App\Filament\Resources\QuizResource;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ViewAttempts extends ListRecords
{
    protected static string $resource = QuizResource::class;

    protected static string $view = 'filament.resources.quiz-resource.pages.view-attempts';

    protected static ?string $title = 'Kết quả Quiz';

    public Quiz $record;

    public function mount(): void
    {
        parent::mount();

        $this->record = Quiz::findOrFail(request()->route('record'));

        // Check permissions
        $user = Auth::user();
        if (! $user->hasRole(['super_admin', 'admin', 'manager', 'teacher'])) {
            abort(403);
        }

        // If teacher, check if they own this quiz
        if ($user->hasRole('teacher') && $this->record->course->teacher_id !== $user->id) {
            abort(403);
        }
    }

    protected function getTableQuery(): Builder
    {
        return QuizAttempt::query()
            ->where('quiz_id', $this->record->id)
            ->whereNotNull('completed_at')
            ->with(['student', 'quiz'])
            ->orderBy('completed_at', 'desc');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('student.name')
                    ->label('Học sinh')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('student.email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('attempt_number')
                    ->label('Lần thử')
                    ->sortable(),

                BadgeColumn::make('score')
                    ->label('Điểm số')
                    ->formatStateUsing(fn ($state) => round($state, 1).'%')
                    ->colors([
                        'success' => fn ($state) => $state >= 80,
                        'warning' => fn ($state) => $state >= 60 && $state < 80,
                        'danger' => fn ($state) => $state < 60,
                    ])
                    ->sortable(),

                BadgeColumn::make('grade')
                    ->label('Xếp loại')
                    ->getStateUsing(function (QuizAttempt $record) {
                        $score = $record->score;
                        if ($score >= 90) {
                            return 'Xuất sắc';
                        }
                        if ($score >= 80) {
                            return 'Giỏi';
                        }
                        if ($score >= 70) {
                            return 'Khá';
                        }
                        if ($score >= 60) {
                            return 'Trung bình';
                        }

                        return 'Yếu';
                    })
                    ->colors([
                        'success' => fn ($state) => in_array($state, ['Xuất sắc', 'Giỏi']),
                        'warning' => fn ($state) => in_array($state, ['Khá', 'Trung bình']),
                        'danger' => fn ($state) => $state === 'Yếu',
                    ]),

                TextColumn::make('time_taken')
                    ->label('Thời gian làm bài')
                    ->getStateUsing(function (QuizAttempt $record) {
                        if (! $record->started_at || ! $record->completed_at) {
                            return 'N/A';
                        }

                        $minutes = $record->started_at->diffInMinutes($record->completed_at);
                        $hours = floor($minutes / 60);
                        $remainingMinutes = $minutes % 60;

                        if ($hours > 0) {
                            return sprintf('%d giờ %d phút', $hours, $remainingMinutes);
                        }

                        return sprintf('%d phút', $remainingMinutes);
                    })
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query->orderByRaw('TIMESTAMPDIFF(MINUTE, started_at, completed_at) '.$direction);
                    }),

                TextColumn::make('started_at')
                    ->label('Bắt đầu')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('completed_at')
                    ->label('Hoàn thành')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                BadgeColumn::make('status')
                    ->label('Trạng thái')
                    ->colors([
                        'success' => 'completed',
                        'warning' => 'in_progress',
                        'danger' => 'abandoned',
                    ])
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'completed' => 'Hoàn thành',
                        'in_progress' => 'Đang làm',
                        'abandoned' => 'Bỏ dở',
                        default => 'Không xác định',
                    }),
            ])
            ->filters([
                SelectFilter::make('student_id')
                    ->label('Học sinh')
                    ->options(function () {
                        return User::whereHas('quizAttempts', function ($query) {
                            $query->where('quiz_id', $this->record->id);
                        })->pluck('name', 'id');
                    })
                    ->searchable(),

                SelectFilter::make('grade_range')
                    ->label('Xếp loại')
                    ->options([
                        'excellent' => 'Xuất sắc (≥90%)',
                        'good' => 'Giỏi (80-89%)',
                        'fair' => 'Khá (70-79%)',
                        'average' => 'Trung bình (60-69%)',
                        'poor' => 'Yếu (<60%)',
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (! $data['value']) {
                            return $query;
                        }

                        return match ($data['value']) {
                            'excellent' => $query->where('score', '>=', 90),
                            'good' => $query->where('score', '>=', 80)->where('score', '<', 90),
                            'fair' => $query->where('score', '>=', 70)->where('score', '<', 80),
                            'average' => $query->where('score', '>=', 60)->where('score', '<', 70),
                            'poor' => $query->where('score', '<', 60),
                            default => $query,
                        };
                    }),

                Filter::make('completed_at')
                    ->form([
                        DatePicker::make('completed_from')
                            ->label('Từ ngày'),
                        DatePicker::make('completed_until')
                            ->label('Đến ngày'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['completed_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('completed_at', '>=', $date),
                            )
                            ->when(
                                $data['completed_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('completed_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Action::make('view_details')
                    ->label('Xem chi tiết')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->url(fn (QuizAttempt $record) => QuizResource::getUrl('quiz-result', [
                        'record' => $this->record->id,
                        'attempt' => $record->id,
                    ]))
                    ->openUrlInNewTab(),

                Action::make('export_result')
                    ->label('Xuất kết quả')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->action('exportResult')
                    ->visible(fn () => Auth::user()->hasRole(['admin', 'manager'])),
            ])
            ->headerActions([
                Action::make('export_all')
                    ->label('Xuất tất cả kết quả')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->action('exportAllResults')
                    ->visible(fn () => Auth::user()->hasRole(['admin', 'manager'])),

                Action::make('statistics')
                    ->label('Thống kê')
                    ->icon('heroicon-o-chart-bar')
                    ->color('info')
                    ->action('showStatistics'),
            ])
            ->defaultSort('completed_at', 'desc');
    }

    public function getQuizStatistics()
    {
        $attempts = $this->getTableQuery()->get();

        if ($attempts->isEmpty()) {
            return [
                'total_attempts' => 0,
                'average_score' => 0,
                'highest_score' => 0,
                'lowest_score' => 0,
                'pass_rate' => 0,
                'average_time' => 'N/A',
            ];
        }

        $totalAttempts = $attempts->count();
        $averageScore = $attempts->avg('score');
        $highestScore = $attempts->max('score');
        $lowestScore = $attempts->min('score');
        $passedAttempts = $attempts->where('score', '>=', 60)->count();
        $passRate = ($passedAttempts / $totalAttempts) * 100;

        // Calculate average time
        $totalMinutes = $attempts->sum(function ($attempt) {
            return $attempt->started_at && $attempt->completed_at
                ? $attempt->started_at->diffInMinutes($attempt->completed_at)
                : 0;
        });

        $averageMinutes = $totalMinutes / $totalAttempts;
        $averageHours = floor($averageMinutes / 60);
        $remainingMinutes = $averageMinutes % 60;

        $averageTime = $averageHours > 0
            ? sprintf('%d giờ %d phút', $averageHours, $remainingMinutes)
            : sprintf('%d phút', $remainingMinutes);

        return [
            'total_attempts' => $totalAttempts,
            'average_score' => round($averageScore, 1),
            'highest_score' => round($highestScore, 1),
            'lowest_score' => round($lowestScore, 1),
            'pass_rate' => round($passRate, 1),
            'average_time' => $averageTime,
        ];
    }

    public function showStatistics()
    {
        $stats = $this->getQuizStatistics();

        $this->dispatch('open-modal', id: 'quiz-statistics', data: $stats);
    }

    public function exportResult(QuizAttempt $attempt)
    {
        // Implementation for exporting individual result
        // This would typically generate a PDF or Excel file
    }

    public function exportAllResults()
    {
        // Implementation for exporting all results
        // This would typically generate a comprehensive report
    }
}
