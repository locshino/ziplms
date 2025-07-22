<?php

namespace App\Filament\Resources\CourseResource\Pages;

use App\Filament\Resources\CourseResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateCourse extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = CourseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Tự động gán người tạo là user đang đăng nhập
        $data['created_by'] = auth()->id();

        return $data;
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Tạo môn học thành công')
            ->body('Một môn học mới đã được thêm vào hệ thống.');
    }
}
