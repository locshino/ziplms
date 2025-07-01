<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Enums\RoleEnum;
use App\Exports\OrganizationsSampleExport;
use App\Exports\UsersSampleExport;
use App\Filament\Actions\ImportExcelAction;
use App\Filament\Resources\UserResource;
use App\Imports\OrganizationImporter;
use App\Imports\UserImporter;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ActionGroup::make([
                Actions\Action::make('new_student')
                    ->label('Tạo Học sinh mới')
                    ->icon('heroicon-o-academic-cap')
                    ->url(static::getResource()::getUrl('create', ['role' => RoleEnum::Student->value])),
                Actions\Action::make('new_teacher')
                    ->label('Tạo Giáo viên mới')
                    ->icon('heroicon-o-user-circle')
                    ->url(static::getResource()::getUrl('create', ['role' => RoleEnum::Teacher->value])),
                Actions\Action::make('new_manager')
                    ->label('Tạo Quản lý mới')
                    ->icon('heroicon-o-briefcase')
                    ->url(static::getResource()::getUrl('create', ['role' => RoleEnum::Manager->value])),
            ])
                ->label('Tạo người dùng mới')
                ->icon('heroicon-m-plus')
                ->button(),

            Actions\ActionGroup::make([
                ImportExcelAction::make('importStudents')
                    ->label('Nhập Học sinh')
                    ->importer(UserImporter::class)
                    ->role(RoleEnum::Student->value)
                    ->sampleExcel(
                        sampleData: UsersSampleExport::sampleData(),
                        fileName: 'students_sample.xlsx',
                        exportClass: UsersSampleExport::class,
                        sampleButtonLabel: 'Tải file mẫu'
                    ),
                ImportExcelAction::make('importTeachers')
                    ->label('Nhập Giáo viên')
                    ->importer(UserImporter::class)
                    ->role(RoleEnum::Teacher->value)
                    ->sampleExcel(
                        sampleData: UsersSampleExport::sampleData(),
                        fileName: 'teachers_sample.xlsx',
                        exportClass: UsersSampleExport::class,
                        sampleButtonLabel: 'Tải file mẫu'
                    ),
                ImportExcelAction::make('importOrganizations')
                    ->label('Nhập Cơ sở')
                    ->importer(OrganizationImporter::class)
                    ->sampleExcel(sampleData: OrganizationsSampleExport::sampleData(), fileName: 'organizations_sample.xlsx', exportClass: OrganizationsSampleExport::class, sampleButtonLabel: 'Tải file mẫu'),
            ])->label('Nhập từ Excel')->icon('heroicon-o-arrow-down-tray')->button()->color('success'),
        ];
    }
}
