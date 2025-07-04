<?php

namespace App\Filament\Resources\ClassMajorResource\Pages;

use App\Filament\Resources\ClassMajorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
// use pxlrbt\FilamentExcel\Actions\ExportAction;  // <-- import ExportAction từ package
// use App\Exports\ClassesMajor;
use Filament\Actions\Action;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ClassesMajorExport;
use Filament\Tables\Actions\ExportAction;
class ListClassMajors extends ListRecords
{
    use ListRecords\Concerns\Translatable;

    protected static string $resource = ClassMajorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
          
          
            Actions\LocaleSwitcher::make(),
        ];
    }
   
}
