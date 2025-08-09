<?php

namespace App\Filament\Resources\AssignmentResource\Pages;

use App\Filament\Resources\AssignmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use HayderHatem\FilamentExcelImport\Actions\FullImportAction;
use App\Filament\Imports\AssignmentImporter;
use Asmit\ResizedColumn\HasResizableColumn;

class ListAssignments extends ListRecords
{
    use HasResizableColumn;
    
    protected static string $resource = AssignmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            
            FullImportAction::make()
                ->importer(AssignmentImporter::class),
        ];
    }
}
