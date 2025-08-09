<?php

namespace App\Filament\Resources\AssignmentResource\Pages;

use App\Filament\Imports\AssignmentImporter;
use App\Filament\Resources\AssignmentResource;
use Asmit\ResizedColumn\HasResizableColumn;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use HayderHatem\FilamentExcelImport\Actions\FullImportAction;

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
