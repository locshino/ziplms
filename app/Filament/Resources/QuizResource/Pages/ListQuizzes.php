<?php

namespace App\Filament\Resources\QuizResource\Pages;

use App\Filament\Resources\QuizResource;
use App\Services\QuizAccessService;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;
use Filament\Tables\Table;

class ListQuizzes extends ListRecords
{
    protected static string $resource = QuizResource::class;

    public function getRecordUrl($record): ?string
    {
        $user = auth()->user();

        if (!$user) {
            return null;
        }

        // For students, redirect to take-quiz page instead of edit
        if ($user->hasRole('student')) {
            $quizAccessService = app(QuizAccessService::class);
            if ($quizAccessService->canTakeQuiz($user, $record)) {
                return QuizResource::getUrl('take-quiz', ['record' => $record]);
            }
            return null; // No URL if can't take quiz
        }

        // For admin, manager - go to edit page
        if ($user->hasRole(['admin', 'manager'])) {
            return QuizResource::getUrl('edit', ['record' => $record]);
        }

        return null;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tạo Quiz mới')
                ->visible(fn() => auth()->user()?->hasRole(['super_admin', 'admin', 'manager', 'teacher']) ?? false),
        ];
    }

    public function getTitle(): string
    {
        return 'Danh sách Quiz';
    }

    public function table(Table $table): Table
    {
        return parent::table($table)
            ->actions([
                // Student actions
                Tables\Actions\Action::make('take_quiz')
                    ->label('Làm bài')
                    ->icon('heroicon-o-play')
                    ->color('success')
                    ->url(fn($record) => QuizResource::getUrl('take-quiz', ['record' => $record]))
                    ->visible(function ($record) {
                        $user = auth()->user();
                        if (!$user || !$user->hasRole('student')) {
                            return false;
                        }

                        $quizAccessService = app(QuizAccessService::class);
                        return $quizAccessService->canTakeQuiz($user, $record);
                    }),

                Tables\Actions\Action::make('view_result')
                    ->label('Xem kết quả')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->url(function ($record) {
                        $user = auth()->user();
                        if (!$user) {
                            return null;
                        }

                        $latestAttempt = $record->attempts()
                            ->where('user_id', $user->id)
                            ->where('status', 'completed')
                            ->latest()
                            ->first();

                        if ($latestAttempt) {
                            return QuizResource::getUrl('quiz-result', ['record' => $record, 'attempt' => $latestAttempt]);
                        }
                        return null;
                    })
                    ->visible(function ($record) {
                        $user = auth()->user();
                        if (!$user || !$user->hasRole('student')) {
                            return false;
                        }

                        return $record->attempts()
                            ->where('user_id', $user->id)
                            ->where('status', 'completed')
                            ->exists();
                    }),

                // Admin/Manager/Teacher actions
                Tables\Actions\EditAction::make()
                    ->label('Chỉnh sửa')
                    ->visible(fn() => auth()->user()?->hasRole(['super_admin', 'admin', 'manager', 'teacher']) ?? false),

                Tables\Actions\Action::make('manage-questions')
                    ->label('Quản lý câu hỏi')
                    ->icon('heroicon-o-question-mark-circle')
                    ->color('info')
                    ->url(fn($record) => QuizResource::getUrl('manage-questions', ['record' => $record]))
                    ->visible(fn() => auth()->user()?->hasRole(['super_admin', 'admin', 'manager']) ?? false),

                Tables\Actions\Action::make('view-attempts')
                    ->label('Xem kết quả')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->url(fn($record) => QuizResource::getUrl('view-attempts', ['record' => $record])),
                Tables\Actions\DeleteAction::make()
                    ->label('Xóa'),
            ]);
    }
}
