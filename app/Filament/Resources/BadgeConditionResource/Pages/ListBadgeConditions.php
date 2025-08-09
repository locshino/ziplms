<?php

namespace App\Filament\Resources\BadgeConditionResource\Pages;

use App\Filament\Imports\BadgeConditionImporter;
use App\Filament\Resources\BadgeConditionResource;
use Asmit\ResizedColumn\HasResizableColumn;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use HayderHatem\FilamentExcelImport\Actions\FullImportAction;

class ListBadgeConditions extends ListRecords
{
    use HasResizableColumn;

    protected static string $resource = BadgeConditionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            FullImportAction::make()
                ->importer(BadgeConditionImporter::class),
        ];
    }
}
