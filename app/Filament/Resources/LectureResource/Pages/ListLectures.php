<?php

namespace App\Filament\Resources\LectureResource\Pages;

use App\Filament\Exports\LectureExporter;
use App\Filament\Imports\LectureImporter;
use App\Filament\Resources\LectureResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLectures extends ListRecords
{
    protected static string $resource = LectureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ExportAction::make()
                ->exporter(LectureExporter::class)
                ->label(__('lecture-resource.actions.export_excel')),

            Actions\ImportAction::make()
                ->importer(LectureImporter::class)
                ->label(__('lecture-resource.actions.import_excel')),

            Actions\CreateAction::make(),
        ];
    }
}
