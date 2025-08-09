<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use HayderHatem\FilamentExcelImport\Actions\FullImportAction;
use App\Filament\Imports\UserImporter;
use Asmit\ResizedColumn\HasResizableColumn;

class ListUsers extends ListRecords
{
    use HasResizableColumn;
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),

            FullImportAction::make()
                ->importer(UserImporter::class),
        ];
    }
}
