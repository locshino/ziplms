<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function getCreatedNotification(): ?Notification
    {
        
        return Notification::make()
            ->success()
            ->title('Tạo người dùng thành công')
            ->body('Người dùng mới đã được thêm vào hệ thống.');
    }
}