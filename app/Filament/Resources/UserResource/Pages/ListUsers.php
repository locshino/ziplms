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
