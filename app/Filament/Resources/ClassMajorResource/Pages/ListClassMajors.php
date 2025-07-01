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
class ListClassMajors extends ListRecords
{
    use ListRecords\Concerns\Translatable;

    protected static string $resource = ClassMajorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
          
             Action::make('export_excel')
            ->label('Export Excel')
            ->icon('heroicon-o-arrow-down-tray')
            ->color('success') 
            ->action(function () {
                return Excel::download(new ClassesMajorExport, 'class_majors.xlsx');
            }),
            Actions\LocaleSwitcher::make(),
        ];
    }
}
