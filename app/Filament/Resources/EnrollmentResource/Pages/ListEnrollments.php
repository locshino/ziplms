<?php

namespace App\Filament\Resources\EnrollmentResource\Pages;

use App\Filament\Imports\EnrollmentImporter;
use App\Filament\Resources\EnrollmentResource;
use Asmit\ResizedColumn\HasResizableColumn;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use HayderHatem\FilamentExcelImport\Actions\FullImportAction;

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
