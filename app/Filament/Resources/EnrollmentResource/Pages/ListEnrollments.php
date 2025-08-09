<?php

namespace App\Filament\Resources\EnrollmentResource\Pages;

use App\Filament\Resources\EnrollmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use HayderHatem\FilamentExcelImport\Actions\FullImportAction;
use App\Filament\Imports\EnrollmentImporter;
use Asmit\ResizedColumn\HasResizableColumn;

class ListEnrollments extends ListRecords
{
    use HasResizableColumn;

    protected static string $resource = EnrollmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            FullImportAction::make()
                ->importer(EnrollmentImporter::class),
        ];
    }
}
