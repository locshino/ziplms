<?php

namespace App\Filament\Resources\CourseStaffAssignmentResource\Pages;

use App\Filament\Resources\CourseStaffAssignmentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCourseStaffAssignment extends CreateRecord
{
    protected static string $resource = CourseStaffAssignmentResource::class;

    protected function afterCreate(): void
    {
        if (isset($this->data['role_tag'])) {
            $this->record->syncTags([$this->data['role_tag']]);
        }
    }
}
