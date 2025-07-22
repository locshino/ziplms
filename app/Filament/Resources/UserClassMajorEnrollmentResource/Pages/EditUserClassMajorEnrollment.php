<?php

namespace App\Filament\Resources\UserClassMajorEnrollmentResource\Pages;

use App\Filament\Resources\UserClassMajorEnrollmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUserClassMajorEnrollment extends EditRecord
{
    protected static string $resource = UserClassMajorEnrollmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),

            
        ];
    }

    public function getTitle(): string
    {
        return __('class_major_lang.Edit User Class Major Enrollment');
    }
}
