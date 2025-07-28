<?php

namespace App\Filament\Resources\CourseStaffAssignmentResource\Pages;

use App\Filament\Resources\CourseStaffAssignmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

/**
 * @property-read \App\Models\CourseStaffAssignment $record
 */
class EditCourseStaffAssignment extends EditRecord
{
    protected static string $resource = CourseStaffAssignmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $this->record->load('tags');

        $data['role_tag'] = $this->record->tags->first()?->name;

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (isset($data['role_tag'])) {
            $this->record->syncTags([$data['role_tag']]);
        }
        unset($data['role_tag']);

        return $data;
    }
}
