<?php

namespace App\Filament\Resources\Users\Tables;

use App\Enums\Status\UserStatus;
use App\Imports\UsersImport;
use App\Libs\Roles\RoleHelper;
use EightyNine\ExcelImport\ExcelImportAction;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Dashboard;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use pxlrbt\FilamentExcel\Actions\ExportBulkAction;
use STS\FilamentImpersonate\Actions\Impersonate;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('avatar')
                    ->collection('avatar')
                    ->label(__('resource_user.table.columns.avatar'))
                    ->circular()
                    // ->defaultImageUrl(asset('images/avatar/default.png'))
                    ->toggleable(),
                TextColumn::make('id')
                    ->label(__('resource_user.table.columns.id'))
                    ->searchable()
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('name')
                    ->label(__('resource_user.table.columns.name'))
                    ->searchable(),
                TextColumn::make('email')
                    ->label(__('resource_user.table.columns.email'))
                    ->searchable(),
                TextColumn::make('email_verified_at')
                    ->label(__('resource_user.table.columns.email_verified_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('status')
                    ->label(__('resource_user.table.columns.status'))
                    ->badge()
                    ->searchable(),
                TextColumn::make('roles.name')
                    ->label(__('resource_user.table.columns.roles.name'))
                    ->badge(),
                // IconColumn::make('force_renew_password')
                //     ->label(__('resource_user.table.columns.force_renew_password'))
                //     ->boolean(),
                TextColumn::make('created_at')
                    ->label(__('resource_user.table.columns.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('resource_user.table.columns.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->label(__('resource_user.table.columns.deleted_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('status')
                    ->options(UserStatus::class)
                    ->label(__('resource_user.table.filters.status')),
                SelectFilter::make('roles')
                    ->relationship('roles', 'name')
                    ->options(RoleHelper::getBaseSystemRoles())
                    ->label(__('resource_user.table.filters.roles'))
                    ->multiple()
                    ->preload(),
            ])
            ->recordActions([
                // ViewAction::make(),
                Impersonate::make()
                    ->redirectTo(Dashboard::getUrl()),
                EditAction::make(),
                DeleteAction::make(),
            ])
            // ->headerActions([
            //     ExcelImportAction::make()
            //         ->slideOver()
            //         ->use(UsersImport::class)
            //         ->beforeUploadField([
            //             TextInput::make('default_password')
            //                 ->password()
            //                 ->helperText('The password to assign to the imported users.'),
            //             Select::make('default_status')
            //                 ->options(UserStatus::class)
            //                 ->helperText('The status to assign to the imported users.'),
            //             Select::make('default_role')
            //                 ->options(RoleHelper::getBaseSystemRoles())
            //                 ->required()
            //                 ->helperText('The role to assign to the imported users.'),
            //         ])
            //         ->sampleFileExcel(
            //             url: url('excel/users.xlsx'),
            //             sampleButtonLabel: 'Download Sample',
            //             customiseActionUsing: fn(Action $action) => $action->color('secondary')
            //                 ->icon('heroicon-m-clipboard')
            //                 ->requiresConfirmation(),
            //         )
            //         ->validateUsing([
            //             'name' => 'required',
            //             'email' => 'required|email',
            //             'password' => 'required|min:8',
            //         ])
            //         ->beforeImport(function (array $data, $livewire, $excelImportAction) {
            //             $dataBonus = [];
            //             $defaultStatus = $data['default_status'];
            //             $defaultPassword = $data['default_password'];
            //             $defaultRole = $data['default_role'];

            //             $dataBonus['status'] = $defaultStatus ?? UserStatus::ACTIVE->value;
            //             $dataBonus['role'] = $defaultRole;

            //             if ($defaultPassword) {
            //                 $dataBonus['password'] = $defaultPassword;
            //                 $dataBonus['password_confirmation'] = $defaultPassword;
            //             }

            //             $excelImportAction->additionalData($dataBonus);
            //         }),
            // ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
                ExportBulkAction::make(),
            ]);
    }
}
