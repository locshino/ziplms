<?php

namespace App\Filament\Resources\UserClassMajorEnrollmentResource\Pages;

use App\Filament\Resources\UserClassMajorEnrollmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Tables\Actions\ExportAction;
use App\Filament\Exports\UserClassMajorEnrollmentExporter;

class ListUserClassMajorEnrollments extends ListRecords
{
  
    protected static string $resource = UserClassMajorEnrollmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),

            
        ];
    }
   
}
