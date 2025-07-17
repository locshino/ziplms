<?php

namespace App\Filament\Resources\LocationResource\Pages;

use App\Filament\Resources\LocationResource;
use Asmit\ResizedColumn\HasResizableColumn;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLocations extends ListRecords
{
    use HasResizableColumn,
        ListRecords\Concerns\Translatable;

    protected static string $resource = LocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\CreateAction::make(),
        ];
    }
}
