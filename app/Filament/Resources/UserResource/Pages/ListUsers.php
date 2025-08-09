<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Imports\UserImporter;
use App\Filament\Resources\UserResource;
use Asmit\ResizedColumn\HasResizableColumn;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use HayderHatem\FilamentExcelImport\Actions\FullImportAction;

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
