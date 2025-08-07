<?php

namespace App\Filament\Resources\QuizResource\Pages;

use App\Filament\Resources\QuizResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use HayderHatem\FilamentExcelImport\Actions\FullImportAction;
use App\Filament\Imports\QuizImporter;
use Asmit\ResizedColumn\HasResizableColumn;

class ListQuizzes extends ListRecords
{
    use HasResizableColumn;
    
    protected static string $resource = QuizResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            
            FullImportAction::make()
                ->importer(QuizImporter::class),
        ];
    }
}
