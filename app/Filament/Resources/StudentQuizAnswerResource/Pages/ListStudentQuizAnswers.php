<?php

namespace App\Filament\Resources\StudentQuizAnswerResource\Pages;

use App\Filament\Resources\StudentQuizAnswerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use HayderHatem\FilamentExcelImport\Actions\FullImportAction;
use App\Filament\Imports\StudentQuizAnswerImporter;
use Asmit\ResizedColumn\HasResizableColumn;

class ListStudentQuizAnswers extends ListRecords
{
    use HasResizableColumn;

    protected static string $resource = StudentQuizAnswerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            FullImportAction::make()->importer(StudentQuizAnswerImporter::class),
        ];
    }
}
