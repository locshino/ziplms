<?php

namespace App\Filament\Resources\ScheduleResource\Pages;

use App\Filament\Exports\ScheduleExporter;
use App\Filament\Imports\ScheduleImporter;
use App\Filament\Resources\ScheduleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSchedules extends ListRecords
{
    use ListRecords\Concerns\Translatable;

    protected static string $resource = ScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\CreateAction::make(),
            Actions\ExportAction::make()
                ->exporter(ScheduleExporter::class),
            Actions\ImportAction::make()
                ->importer(ScheduleImporter::class),
        ];
    }
}
