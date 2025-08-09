<?php

namespace App\Filament\Resources\QuestionResource\Pages;

use App\Filament\Imports\QuestionImporter;
use App\Filament\Resources\QuestionResource;
use Asmit\ResizedColumn\HasResizableColumn;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use HayderHatem\FilamentExcelImport\Actions\FullImportAction;

class ListQuestions extends ListRecords
{
    use HasResizableColumn;

    protected static string $resource = QuestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            FullImportAction::make()->importer(QuestionImporter::class),
        ];
    }
}
