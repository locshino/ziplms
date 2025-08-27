<?php

namespace App\Filament\Resources\Badges\Pages;

use App\Filament\Resources\Badges\BadgeResource;
use Asmit\ResizedColumn\HasResizableColumn;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBadges extends ListRecords
{
    use HasResizableColumn;

    protected static string $resource = BadgeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
