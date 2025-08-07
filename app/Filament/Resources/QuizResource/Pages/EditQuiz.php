<?php

namespace App\Filament\Resources\QuizResource\Pages;

use App\Filament\Resources\QuizResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditQuiz extends EditRecord
{
    protected static string $resource = QuizResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('manage-questions')
                ->label('Quản lý câu hỏi')
                ->icon('heroicon-o-question-mark-circle')
                ->color('info')
                ->url(fn () => QuizResource::getUrl('manage-questions', ['record' => $this->record]))
                ->visible(fn () => auth()->user()->hasRole(['super_admin', 'admin', 'manager'])),
                
            Actions\DeleteAction::make()
                ->label('Xóa Quiz'),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Quiz đã được cập nhật')
            ->body('Thông tin quiz đã được lưu thành công.');
    }

    public function getTitle(): string
    {
        return 'Chỉnh sửa Quiz';
    }
}