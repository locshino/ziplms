<?php

namespace App\Filament\Resources\BadgeConditions\Pages;

use App\Filament\Resources\BadgeConditions\BadgeConditionResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditBadgeCondition extends EditRecord
{
    protected static string $resource = BadgeConditionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
