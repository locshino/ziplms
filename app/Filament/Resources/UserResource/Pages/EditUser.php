<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Services\Interfaces\UserServiceInterface;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $photo = $this->form->getState()['avatar'] ?? null;

        if ($photo instanceof \Illuminate\Http\UploadedFile) {
            app(UserServiceInterface::class)->updateAvatar($this->record, $photo);
        }

        unset($data['avatar']);

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
