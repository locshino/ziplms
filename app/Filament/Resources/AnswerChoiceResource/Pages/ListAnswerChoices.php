<?php

namespace App\Filament\Resources\AnswerChoiceResource\Pages;

use App\Filament\Resources\AnswerChoiceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use HayderHatem\FilamentExcelImport\Actions\FullImportAction;
use App\Filament\Imports\AnswerChoiceImporter;
use Asmit\ResizedColumn\HasResizableColumn;

class ListAnswerChoices extends ListRecords
{
    use HasResizableColumn;

    protected static string $resource = AnswerChoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            FullImportAction::make()
                ->importer(AnswerChoiceImporter::class),
        ];
    }
}
