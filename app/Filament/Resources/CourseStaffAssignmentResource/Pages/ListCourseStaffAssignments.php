<?php

namespace App\Filament\Resources\CourseStaffAssignmentResource\Pages;

use App\Filament\Resources\CourseStaffAssignmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCourseStaffAssignments extends ListRecords
{
    protected static string $resource = CourseStaffAssignmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
