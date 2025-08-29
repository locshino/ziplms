<?php

namespace App\Filament\Resources\Submissions\Tables;

use App\Enums\Status\SubmissionStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;
use pxlrbt\FilamentExcel\Actions\ExportBulkAction;

class SubmissionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label(__('resource_submission.table.columns.id'))
                    ->searchable()
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('assignment.title')
                    ->label(__('resource_submission.table.columns.assignment.title'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('student.name')
                    ->label(__('resource_submission.table.columns.student.name'))
                    ->searchable(),
                TextColumn::make('status')
                    ->label(__('resource_submission.table.columns.status'))
                    ->searchable(),
                TextColumn::make('submitted_at')
                    ->label(__('resource_submission.table.columns.submitted_at'))
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('grader.name')
                    ->label(__('resource_submission.table.columns.grader.name'))
                    ->searchable(),
                TextColumn::make('points')
                    ->label(__('resource_submission.table.columns.points'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('graded_at')
                    ->label(__('resource_submission.table.columns.graded_at'))
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('resource_submission.table.columns.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('resource_submission.table.columns.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->label(__('resource_submission.table.columns.deleted_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultGroup('assignment.title')
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('assignment')
                    ->relationship('assignment', 'title')
                    ->searchable(),
                SelectFilter::make('student')
                    ->relationship('student', 'name')
                    ->searchable(),
                SelectFilter::make('grader')
                    ->relationship('grader', 'name')
                    ->searchable(),
                DateRangeFilter::make('submitted_at'),
                DateRangeFilter::make('graded_at'),
                SelectFilter::make('status')
                    ->options(SubmissionStatus::class),
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
                ExportBulkAction::make(),
            ]);
    }
}
