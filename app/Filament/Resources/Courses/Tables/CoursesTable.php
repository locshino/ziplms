<?php

namespace App\Filament\Resources\Courses\Tables;

use App\Enums\Status\CourseStatus;
use App\Filament\Imports\CourseImporter;
use App\Filament\Tables\Filters\SelectTagsFilter;
use App\Models\Course;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\ImportAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieTagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class CoursesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label(__('resource_course.table.columns.id'))
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('title')
                    ->label(__('resource_course.table.columns.title'))
                    ->searchable(),
                TextColumn::make('slug')
                    ->label(__('resource_course.table.columns.slug'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('teacher.name')
                    ->label(__('resource_course.table.columns.teacher.name'))
                    ->searchable(),
                TextColumn::make('start_at')
                    ->label(__('resource_course.table.columns.start_at'))
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('end_at')
                    ->label(__('resource_course.table.columns.end_at'))
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('status')
                    ->label(__('resource_course.table.columns.status'))
                    ->searchable(),
                TextColumn::make('price')
                    ->label(__('resource_course.table.columns.price'))
                    ->money(currency: config('ziplms.currency.default'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                IconColumn::make('is_featured')
                    ->label(__('resource_course.table.columns.is_featured'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->boolean(),
                SpatieTagsColumn::make('tags')
                    ->label(__('resource_course.table.columns.tags'))
                    ->type(Course::class)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label(__('resource_course.table.columns.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('resource_course.table.columns.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->label(__('resource_course.table.columns.deleted_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('teacher')
                    ->relationship('teacher', 'name')
                    ->searchable()
                    ->preload(),
                DateRangeFilter::make('start_at'),
                DateRangeFilter::make('end_at'),
                SelectFilter::make('status')
                    ->options(CourseStatus::class),
                SelectTagsFilter::make('tags')
                    ->type(Course::class),
                TernaryFilter::make('is_featured'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->headerActions([
                ImportAction::make('Import')
                    ->importer(CourseImporter::class)
                    ->options([
                        'default_status' => CourseStatus::DRAFT->value,
                    ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
                ExportBulkAction::make()->exports([
                    ExcelExport::make()->withColumns([
                        Column::make('title')
                            ->heading(__('resource_course.table.columns.title')),
                        Column::make('teacher.name')
                            ->heading(__('resource_course.table.columns.teacher.name')),
                        Column::make('start_at')
                            ->heading(__('resource_course.table.columns.start_at')),
                        Column::make('end_at')
                            ->heading(__('resource_course.table.columns.end_at')),
                        Column::make('status')
                            ->heading(__('resource_course.table.columns.status')),
                        Column::make('price')
                            ->heading(__('resource_course.table.columns.price')),
                        Column::make('created_at')
                            ->heading(__('resource_course.table.columns.created_at')),
                    ])
                        ->askForFilename()
                        ->askForWriterType()
                        ->queue()
                        ->withChunkSize(100),
                ]),
            ]);
    }
}
