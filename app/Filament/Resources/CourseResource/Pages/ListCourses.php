<?php

namespace App\Filament\Resources\CourseResource\Pages;

use App\Filament\Imports\CourseImporter;
use App\Filament\Resources\CourseResource;
use Asmit\ResizedColumn\HasResizableColumn;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use HayderHatem\FilamentExcelImport\Actions\FullImportAction;

class ListCourses extends ListRecords
{
    use HasResizableColumn;

    protected static string $resource = CourseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),

            FullImportAction::make()
                ->importer(CourseImporter::class),
        ];
    }
}
