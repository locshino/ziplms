<?php

namespace App\Filament\Resources\CourseResource\Pages;

use App\Filament\Resources\CourseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use HayderHatem\FilamentExcelImport\Actions\FullImportAction;
use App\Filament\Imports\CourseImporter;
use Asmit\ResizedColumn\HasResizableColumn;

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
