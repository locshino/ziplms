<?php

namespace App\Filament\Resources\CourseEnrollmentResource\Pages;

use App\Filament\Resources\CourseEnrollmentResource;
use Asmit\ResizedColumn\HasResizableColumn;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCourseEnrollments extends ListRecords
{
    use HasResizableColumn,
    ListRecords\Concerns\Translatable;

    protected static string $resource = CourseEnrollmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\CreateAction::make(),
        ];
    }
}
