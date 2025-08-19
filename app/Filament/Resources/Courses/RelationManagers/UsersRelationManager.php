<?php

namespace App\Filament\Resources\Courses\RelationManagers;

use App\Filament\Resources\Users\UserResource;
use App\Libs\Roles\RoleHelper;
use Filament\Actions\AttachAction;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersRelationManager extends RelationManager
{
    protected static string $relationship = 'users';

    protected static ?string $relatedResource = UserResource::class;

    public static function canViewForRecord($ownerRecord, $pageClass): bool
    {
        return RoleHelper::isAdministrative();
    }

    public function table(Table $table): Table
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
                TextColumn::make('pivot.start_at')
                    ->label('Start At')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('pivot.end_at')
                    ->label('End At')
                    ->dateTime()
                    ->sortable(),
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
            ->headerActions([
                CreateAction::make(),
                AttachAction::make()
                    ->schema(fn (AttachAction $action): array => [
                        $action->getRecordSelect(),
                        DateTimePicker::make('start_at')
                            ->before('end_at')
                            ->required(),
                        DateTimePicker::make('end_at')
                            ->after('start_at')
                            ->required(),
                    ])
                    ->recordSelectSearchColumns(['name', 'email'])
                    ->multiple(),
            ]);
    }
}
