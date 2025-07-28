<?php

namespace App\Filament\Resources\UserClassMajorEnrollmentResource\Pages;

use App\Filament\Resources\UserClassMajorEnrollmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUserClassMajorEnrollments extends ListRecords
{
    protected static string $resource = UserClassMajorEnrollmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label(__('user_class_major_enrollments.add')),
        ];
    }

    public function getTitle(): string
    {
        return __('class_major_lang.List of User Class Major Enrollments');
    }
}
