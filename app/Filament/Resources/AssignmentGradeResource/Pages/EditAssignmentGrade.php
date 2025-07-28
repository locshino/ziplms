<?php

namespace App\Filament\Resources\AssignmentGradeResource\Pages;

use App\Filament\Resources\AssignmentGradeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\Concerns\Translatable;

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
    protected function mutateFormDataBeforeSave(array $data): array
    {

        $data['grade.grade'] = 'graded';



        return $data;
    }
}
