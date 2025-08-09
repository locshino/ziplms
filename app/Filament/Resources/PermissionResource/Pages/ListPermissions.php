<?php

namespace App\Filament\Resources\PermissionResource\Pages;

use App\Filament\Imports\PermissionImporter;
use App\Filament\Resources\PermissionResource;
use Asmit\ResizedColumn\HasResizableColumn;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use HayderHatem\FilamentExcelImport\Actions\FullImportAction;

class ListPermissions extends ListRecords
{
    use HasResizableColumn;

    protected static string $resource = PermissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            FullImportAction::make()
                ->importer(PermissionImporter::class),
        ];
    }
}
