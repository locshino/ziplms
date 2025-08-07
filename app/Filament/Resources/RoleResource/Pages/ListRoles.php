<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use HayderHatem\FilamentExcelImport\Actions\FullImportAction;
use App\Filament\Imports\RoleImporter;
use Asmit\ResizedColumn\HasResizableColumn;

class ListRoles extends ListRecords
{
    use HasResizableColumn;
    
    protected static string $resource = RoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            
            FullImportAction::make()
                ->importer(RoleImporter::class),
        ];
    }
}
