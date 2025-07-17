<?php

namespace App\Filament\Resources\ScheduleResource\Pages;

use App\Filament\Resources\ScheduleResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Nben\FilamentRecordNav;

class ViewSchedule extends ViewRecord
{
    use FilamentRecordNav\Concerns\WithRecordNavigation,
        ViewRecord\Concerns\Translatable;

    protected static string $resource = ScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\EditAction::make(),

            FilamentRecordNav\Actions\PreviousRecordAction::make(),
            FilamentRecordNav\Actions\NextRecordAction::make(),
        ];
    }
}
