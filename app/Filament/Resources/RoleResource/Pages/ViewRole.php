<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewRole extends ViewRecord
{
    protected static string $resource = RoleResource::class;

    protected function getActions(): array
    {
        $actions = [];

        // Only show edit action for non-system roles
        if (! $this->record->is_system) {
            $actions[] = Actions\EditAction::make();
        }

        return $actions;
    }
}
