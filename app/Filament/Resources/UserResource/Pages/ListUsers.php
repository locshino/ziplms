<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Enums\RoleEnum;
use App\Filament\Exports\UserExporter;
use App\Filament\Imports\UserImporter;
use App\Filament\Resources\UserResource;
use Asmit\ResizedColumn\HasResizableColumn;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    use HasResizableColumn,
        ListRecords\Concerns\Translatable;

    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),

            Actions\CreateAction::make(),

            Actions\ExportAction::make()
                ->exporter(UserExporter::class),

            Actions\ActionGroup::make([
                Actions\ImportAction::make('importStudents')
                    ->label(__('user-resource.actions.import_students'))
                    ->importer(UserImporter::class)
                    ->options(['role' => RoleEnum::Student->value]),

                Actions\ImportAction::make('importTeachers')
                    ->label(__('user-resource.actions.import_teachers'))
                    ->importer(UserImporter::class)
                    ->options(['role' => RoleEnum::Teacher->value]),
            ])
                ->label(__('user-resource.actions.import_from_csv'))
                ->icon('heroicon-o-document-arrow-up')
                ->button(),
        ];
    }
}
