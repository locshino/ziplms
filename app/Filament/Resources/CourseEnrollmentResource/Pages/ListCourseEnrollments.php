<?php

namespace App\Filament\Resources\CourseEnrollmentResource\Pages;

use App\Filament\Resources\CourseEnrollmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Asmit\ResizedColumn\HasResizableColumn;
class ListCourseEnrollments extends ListRecords
{
    use HasResizableColumn;
    protected static string $resource = CourseEnrollmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
