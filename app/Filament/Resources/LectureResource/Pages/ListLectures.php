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
                ->label('Xuất Excel'),
            Actions\ImportAction::make()
                ->importer(LectureImporter::class)
                ->label('Nhập Excel'),

            Actions\CreateAction::make(),
        ];
    }
}
