<?php

namespace App\Filament\Resources\BadgeConditions\Pages;

use App\Filament\Resources\BadgeConditions\BadgeConditionResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewBadgeCondition extends ViewRecord
{
    protected static string $resource = BadgeConditionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
