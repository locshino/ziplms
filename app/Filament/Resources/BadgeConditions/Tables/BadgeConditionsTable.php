<?php

namespace App\Filament\Resources\BadgeConditions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class BadgeConditionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label(__('resource_badge_condition.table.columns.id'))
                    ->searchable(),
                TextColumn::make('title')
                    ->label(__('resource_badge_condition.table.columns.title'))
                    ->searchable(),
                TextColumn::make('condition_type')
                    ->label(__('resource_badge_condition.table.columns.condition_type'))
                    ->searchable(),
                TextColumn::make('status')
                    ->label(__('resource_badge_condition.table.columns.status'))
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label(__('resource_badge_condition.table.columns.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('resource_badge_condition.table.columns.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->label(__('resource_badge_condition.table.columns.deleted_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
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
