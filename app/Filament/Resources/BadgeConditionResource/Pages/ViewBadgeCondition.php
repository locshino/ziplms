<?php

namespace App\Filament\Resources\BadgeConditionResource\Pages;

use App\Filament\Resources\BadgeConditionResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBadgeCondition extends ViewRecord
{
    protected static string $resource = BadgeConditionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
