<?php

namespace App\Filament\Resources\ClassMajorResource\Pages;

use App\Filament\Resources\ClassMajorResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use EightyNine\ExcelImport\Actions\ImportExcelAction; // ✅ Import đúng package
use App\Imports\UserClassMajorEnrollmentImport; // ✅ Import class import của bạn

class CreateClassMajor extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = ClassMajorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),

            ImportExcelAction::make()
                ->label('Import Excel')
                ->color('success')
                ->icon('heroicon-o-arrow-up-tray')
                ->importer(UserClassMajorEnrollmentImport::class),
        ];
    }
}
