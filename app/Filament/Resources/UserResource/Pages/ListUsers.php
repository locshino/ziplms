<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Exports\UsersSampleExport;
use App\Filament\Actions\ImportExcelAction;
use App\Filament\Resources\UserResource;
use App\Imports\UserImporter;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),

            // ImportExcelAction::make('importUsers')
            //     ->label('Import Manager Users')
            //     ->importer(UserImporter::class)
            //     ->role('manager') // Optional
            //     // CORRECTED: Use the correct method signature for sampleExcel
            //     ->sampleExcel(
            //         sampleData: UsersSampleExport::sampleData(),
            //         fileName: 'users_sample.xlsx',
            //         exportClass: UsersSampleExport::class,
            //         sampleButtonLabel: 'Download Sample',
            //     ),

            \EightyNine\ExcelImport\ExcelImportAction::make()
                ->slideOver()
                ->color("primary")
                ->use(UserImporter::class),
        ];
    }
}
