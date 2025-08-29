<?php

namespace App\Filament\Resources\Quizzes\Tables;

use App\Filament\Tables\Filters\SelectTagsFilter;
use App\Models\Quiz;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieTagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use pxlrbt\FilamentExcel\Actions\ExportBulkAction;

class QuizzesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label(__('resource_quiz.table.columns.id'))
                    ->copyable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('title')
                    ->label(__('resource_quiz.table.columns.title'))
                    ->searchable(),
                TextColumn::make('max_attempts')
                    ->label(__('resource_quiz.table.columns.max_attempts'))
                    ->numeric()
                    ->sortable(),
                IconColumn::make('is_single_session')
                    ->label(__('resource_quiz.table.columns.is_single_session'))
                    ->boolean(),
                TextColumn::make('time_limit_minutes')
                    ->label(__('resource_quiz.table.columns.time_limit_minutes'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->label(__('resource_quiz.table.columns.status'))
                    ->searchable(),
                SpatieTagsColumn::make('tags')
                    ->label(__('resource_quiz.table.columns.tags'))
                    ->type(Quiz::class)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label(__('resource_quiz.table.columns.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('resource_quiz.table.columns.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->label(__('resource_quiz.table.columns.deleted_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
                SelectTagsFilter::make('tags')
                    ->type(Quiz::class),
            ])
            ->recordActions([
                // ViewAction::make(),
                EditAction::make(),
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
