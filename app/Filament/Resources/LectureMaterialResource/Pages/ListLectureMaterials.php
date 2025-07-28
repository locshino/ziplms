<?php

namespace App\Filament\Resources\LectureMaterialResource\Pages;

use App\Filament\Resources\LectureMaterialResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLectureMaterials extends ListRecords
{
    protected static string $resource = LectureMaterialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}