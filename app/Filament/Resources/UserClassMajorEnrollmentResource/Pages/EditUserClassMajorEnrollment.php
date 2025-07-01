<?php

namespace App\Filament\Resources\UserClassMajorEnrollmentResource\Pages;

use App\Filament\Resources\UserClassMajorEnrollmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUserClassMajorEnrollment extends EditRecord
{
     use EditRecord\Concerns\Translatable;

    protected static string $resource = UserClassMajorEnrollmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\LocaleSwitcher::make(),
        ];
    }
}
