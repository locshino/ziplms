<?php

namespace App\Filament\Resources\CourseStaffAssignmentResource\Pages;

use App\Filament\Resources\CourseStaffAssignmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Asmit\ResizedColumn\HasResizableColumn;

class ListCourseStaffAssignments extends ListRecords
{
    use HasResizableColumn,
        ListRecords\Concerns\Translatable;
    protected static string $resource = CourseStaffAssignmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\CreateAction::make(),
        ];
    }
}
