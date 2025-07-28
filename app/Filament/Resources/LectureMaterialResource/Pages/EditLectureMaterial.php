<?php

namespace App\Filament\Resources\LectureMaterialResource\Pages;

use App\Filament\Resources\LectureMaterialResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLectureMaterial extends EditRecord
{
    protected static string $resource = LectureMaterialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
