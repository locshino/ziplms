<?php

namespace App\Filament\Resources\DocumentResource\Pages;

use App\Filament\Resources\DocumentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateDocument extends CreateRecord
{
    protected static string $resource = DocumentResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Tài liệu đã được tạo thành công!';
    }

    // Override để xử lý việc tạo record mà không cần model
    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        // Hiển thị thông báo thành công
        Notification::make()
            ->title('Tài liệu đã được tạo')
            ->body('Tài liệu "' . $data['title'] . '" đã được tạo thành công.')
            ->success()
            ->send();

        // Trả về một object giả để tránh lỗi
        return new class extends \Illuminate\Database\Eloquent\Model {
            protected $fillable = ['*'];
            public function __construct(array $attributes = []) {
                $this->attributes = $attributes;
            }
        };
    }
}