<?php

namespace App\Filament\Resources\BadgeConditions\Pages;

use App\Filament\Resources\BadgeConditions\BadgeConditionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBadgeConditions extends ListRecords
{
    protected static string $resource = BadgeConditionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
