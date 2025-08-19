<?php

namespace App\Filament\Resources\Users\Tables;

use App\Enums\Status\UserStatus;
use App\Enums\System\RoleSystem;
use App\Libs\Roles\RoleHelper;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

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
                    ->size(40)
                    ->defaultImageUrl(url('/images/avatar/default.png'))
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
                    ->label(__('resource_user.table.columns.roles'))
                    ->badge()
                    ->separator(', ')
                    ->searchable(),
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
                    ->options(Role::all()->pluck('name', 'name'))
                    ->label(__('resource_user.table.filters.roles'))
                    ->multiple()
                    ->preload(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
