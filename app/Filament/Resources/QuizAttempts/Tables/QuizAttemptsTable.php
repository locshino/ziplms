<?php

namespace App\Filament\Resources\QuizAttempts\Tables;

use App\Enums\Status\QuizAttemptStatus;
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
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class QuizAttemptsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label(__('resource_quiz_attempt.table.columns.id'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('quiz.title')
                    ->label(__('resource_quiz_attempt.table.columns.quiz.title'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('student.name')
                    ->label(__('resource_quiz_attempt.table.columns.student.name'))
                    ->searchable(),
                TextColumn::make('points')
                    ->label(__('resource_quiz_attempt.table.columns.points'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('start_at')
                    ->label(__('resource_quiz_attempt.table.columns.start_at'))
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('end_at')
                    ->label(__('resource_quiz_attempt.table.columns.end_at'))
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('status')
                    ->label(__('resource_quiz_attempt.table.columns.status'))
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label(__('resource_quiz_attempt.table.columns.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('resource_quiz_attempt.table.columns.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->label(__('resource_quiz_attempt.table.columns.deleted_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultGroup('quiz.title')
            ->filters([
                SelectFilter::make('quiz.title')
                    ->label('Quiz')
                    ->searchable()
                    ->relationship('quiz', 'title'),
                SelectFilter::make('student.name')
                    ->label('Student')
                    ->searchable()
                    ->relationship('student', 'name'),
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(QuizAttemptStatus::class),
                DateRangeFilter::make('start_at'),
                DateRangeFilter::make('end_at'),
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
                ExportBulkAction::make()->exports([
                    ExcelExport::make()->withColumns([
                        Column::make('id'),
                        Column::make('quiz.title'),
                        Column::make('student.name'),
                        Column::make('points'),
                        Column::make('start_at'),
                        Column::make('end_at'),
                        Column::make('status'),
                        Column::make('created_at'),
                        Column::make('updated_at'),
                    ])
                        // Optional: you can customize the filename
                        ->withFilename(fn($resource) => $resource::getModelLabel() . '-' . date('Y-m-d')),
                ]),
            ]);
    }
}
