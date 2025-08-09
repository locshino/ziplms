<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Services\Interfaces\UserServiceInterface;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        unset($data['avatar']);

        return $data;
    }

    // Sau khi user được tạo (đã có $this->record), xử lý upload ảnh
    protected function afterCreate(): void
    {
        $photo = $this->form->getState()['avatar'] ?? null;

        if ($photo instanceof \Illuminate\Http\UploadedFile) {
            app(UserServiceInterface::class)->updateAvatar($this->record, $photo);
        }
    }
}
