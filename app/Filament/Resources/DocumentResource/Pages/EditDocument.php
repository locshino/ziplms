<?php

namespace App\Filament\Resources\DocumentResource\Pages;

use App\Filament\Resources\DocumentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditDocument extends EditRecord
{
    protected static string $resource = DocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Xóa'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Tài liệu đã được cập nhật!';
    }

    // Override để xử lý việc cập nhật record mà không cần model
    protected function handleRecordUpdate(\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model
    {
        // Hiển thị thông báo thành công
        Notification::make()
            ->title('Tài liệu đã được cập nhật')
            ->body('Tài liệu đã được cập nhật thành công.')
            ->success()
            ->send();

        // Cập nhật attributes của record
        $record->fill($data);
        return $record;
    }
}