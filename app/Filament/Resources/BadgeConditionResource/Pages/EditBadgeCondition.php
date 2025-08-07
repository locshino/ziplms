<?php

namespace App\Filament\Resources\BadgeConditionResource\Pages;

use App\Filament\Resources\BadgeConditionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBadgeCondition extends EditRecord
{
    protected static string $resource = BadgeConditionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
