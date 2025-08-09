<?php

namespace App\Filament\Resources\QuizResource\Pages;

use App\Filament\Resources\QuizResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditQuiz extends EditRecord
{
    protected static string $resource = QuizResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
                ->visible(fn () => $this->record->attempts()->count() === 0)
                ->tooltip(fn () => $this->record->attempts()->count() > 0 ? 'Không thể xóa quiz đã có người làm bài' : null),
        ];
    }

    protected function beforeSave(): void
    {
        // Kiểm tra xem quiz đã có người làm bài chưa
        if ($this->record->attempts()->count() > 0) {
            // Chỉ cho phép chỉnh sửa một số trường nhất định
            $allowedFields = ['description', 'end_at'];
            $originalData = $this->record->getOriginal();
            $newData = $this->data;

            foreach ($newData as $field => $value) {
                if (! in_array($field, $allowedFields) && $originalData[$field] != $value) {
                    Notification::make()
                        ->title('Không thể chỉnh sửa')
                        ->body('Quiz đã có người làm bài. Chỉ có thể chỉnh sửa mô tả và thời gian kết thúc.')
                        ->danger()
                        ->send();

                    $this->halt();

                    return;
                }
            }
        }
    }
}
