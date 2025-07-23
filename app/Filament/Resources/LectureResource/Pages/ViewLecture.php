<?php

namespace App\Filament\Resources\LectureResource\Pages;

use App\Filament\Resources\LectureResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Nben\FilamentRecordNav;

class ViewLecture extends ViewRecord
{
    protected static string $resource = LectureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),

            FilamentRecordNav\Actions\PreviousRecordAction::make(),
            FilamentRecordNav\Actions\NextRecordAction::make(),
        ];
    }
}
