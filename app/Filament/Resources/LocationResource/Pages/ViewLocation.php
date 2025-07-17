<?php

namespace App\Filament\Resources\LocationResource\Pages;

use App\Filament\Resources\LocationResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Nben\FilamentRecordNav;

class ViewLocation extends ViewRecord
{
    use FilamentRecordNav\Concerns\WithRecordNavigation,
        ViewRecord\Concerns\Translatable;

    protected static string $resource = LocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),

            FilamentRecordNav\Actions\PreviousRecordAction::make(),
            FilamentRecordNav\Actions\NextRecordAction::make(),
        ];
    }
}
