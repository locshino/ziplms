<?php

namespace App\Filament\Resources\UserClassMajorEnrollmentResource\Pages;

use App\Filament\Resources\UserClassMajorEnrollmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UserClassMajorEnrollmentResourceExport;
class ListUserClassMajorEnrollments extends ListRecords
{
    use ListRecords\Concerns\Translatable;
    protected static string $resource = UserClassMajorEnrollmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
             Action::make('export_excel')
                ->label('Export Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(function () {
                    return Excel::download(new UserClassMajorEnrollmentResourceExport, 'user_class_major_enrollments.xlsx');
                }),

            Actions\LocaleSwitcher::make(),
            
        ];
    }
}
