<?php

namespace App\Filament\Resources\BadgeResource\Pages;

use App\Filament\Imports\BadgeImporter;
use App\Filament\Resources\BadgeResource;
use Asmit\ResizedColumn\HasResizableColumn;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use HayderHatem\FilamentExcelImport\Actions\FullImportAction;

class ListBadges extends ListRecords
{
    use HasResizableColumn;

    protected static string $resource = BadgeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            FullImportAction::make()
                ->importer(BadgeImporter::class),
        ];
    }
}
