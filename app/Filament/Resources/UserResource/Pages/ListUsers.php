<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Enums\RoleEnum;
use App\Filament\Exports\UserExporter;
use App\Filament\Imports\UserImporter;
use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),

            Actions\ExportAction::make()
                ->label('Xuất Excel')
                ->exporter(UserExporter::class)
                ->icon('heroicon-o-document-arrow-down'),

            Actions\ActionGroup::make([
                Actions\ImportAction::make('importStudents')
                    ->label('Nhập Học sinh')
                    ->importer(UserImporter::class)
                    ->options(['role' => RoleEnum::Student->value]),

                Actions\ImportAction::make('importTeachers')
                    ->label('Nhập Giáo viên')
                    ->importer(UserImporter::class)
                    ->options(['role' => RoleEnum::Teacher->value]),
            ])
                ->label('Nhập từ CSV')
                ->icon('heroicon-o-document-arrow-up')
                ->button(),
        ];
    }
}
