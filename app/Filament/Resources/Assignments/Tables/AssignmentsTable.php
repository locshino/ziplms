<?php

namespace App\Filament\Resources\Assignments\Tables;

use App\Enums\Status\AssignmentStatus;
use App\Filament\Imports\AssignmentImporter;
use App\Filament\Tables\Filters\SelectTagsFilter;
use App\Models\Assignment;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\ImportAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\SpatieTagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use pxlrbt\FilamentExcel\Actions\ExportBulkAction;

class AssignmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label(__('resource_assignment.table.columns.id'))
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('title')
                    ->label(__('resource_assignment.table.columns.title'))
                    ->searchable(),
                TextColumn::make('max_points')
                    ->label(__('resource_assignment.table.columns.max_points'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('max_attempts')
                    ->label(__('resource_assignment.table.columns.max_attempts'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->label(__('resource_assignment.table.columns.status'))
                    ->searchable(),
                SpatieTagsColumn::make('tags')
                    ->label(__('resource_assignment.table.columns.tags'))
                    ->type(Assignment::class)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label(__('resource_assignment.table.columns.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('resource_assignment.table.columns.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->label(__('resource_assignment.table.columns.deleted_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('status')
                    ->options(AssignmentStatus::class),
                SelectTagsFilter::make('tags')
                    ->type(Assignment::class),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->headerActions([
                ImportAction::make('Import')
                    ->importer(AssignmentImporter::class)
                    ->options([
                        'default_status' => AssignmentStatus::PUBLISHED->value,
                    ]),
            ])
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
