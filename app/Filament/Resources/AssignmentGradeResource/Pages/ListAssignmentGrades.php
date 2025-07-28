<?php

namespace App\Filament\Resources\AssignmentGradeResource\Pages;

use App\Filament\Resources\AssignmentGradeResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class ListAssignmentGrades extends ListRecords
{
    use ListRecords\Concerns\Translatable;

    protected static string $resource = AssignmentGradeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),


        ];
    }
    public function getTitle(): string
    {
        return __('assignment_grade.label.assignment_grades');
    }
}
