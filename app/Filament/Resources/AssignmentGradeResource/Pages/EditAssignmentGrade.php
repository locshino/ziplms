<?php

namespace App\Filament\Resources\AssignmentGradeResource\Pages;

use App\Filament\Resources\AssignmentGradeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAssignmentGrade extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    protected static string $resource = AssignmentGradeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    public function getTitle(): string
    {
        return __('assignment_grade.label.assignment_grade');
    }

    protected function afterSave(): void
    {
        $submission = $this->record->submission;

        if ($submission) {
            $submission->status = 'graded';
            $submission->save();
        }
    }
}
