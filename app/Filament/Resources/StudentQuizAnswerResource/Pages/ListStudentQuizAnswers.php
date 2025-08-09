<?php

namespace App\Filament\Resources\StudentQuizAnswerResource\Pages;

use App\Filament\Imports\StudentQuizAnswerImporter;
use App\Filament\Resources\StudentQuizAnswerResource;
use Asmit\ResizedColumn\HasResizableColumn;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use HayderHatem\FilamentExcelImport\Actions\FullImportAction;

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
