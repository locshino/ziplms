<?php

namespace App\Filament\Resources;

use App\Enums\LocationType;
use App\Enums\SchedulableType;
use App\Filament\Exports\ScheduleExporter;
use App\Filament\Resources\ScheduleResource\Pages;
use App\Models\Schedule;
use App\Repositories\Contracts\ScheduleRepositoryInterface;
use App\States\Status;
use Filament\Forms;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SpatieTagsColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ScheduleResource extends Resource
{
    use Translatable;

    protected static ?string $model = Schedule::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    // Use translation keys for model labels
    public static function getModelLabel(): string
    {
        return __('schedule-resource.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('schedule-resource.model_label_plural');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('schedule-resource.form.section.main'))
                    ->schema([
                        Forms\Components\MorphToSelect::make('schedulable')
                            ->label(__('schedule-resource.form.associated_with'))
                            ->types(SchedulableType::getMorphToSelectTypes())
                            ->searchable()
                            ->required(),

                        Forms\Components\TextInput::make('title')
                            ->label(__('schedule-resource.form.title'))
                            ->required(),
                        Forms\Components\RichEditor::make('description')
                            ->label(__('schedule-resource.form.description'))
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make(__('schedule-resource.form.section.time_location'))
                    ->schema([
                        Forms\Components\DateTimePicker::make('start_time')
                            ->label(__('schedule-resource.form.start_time'))
                            ->required(),

                        Forms\Components\DateTimePicker::make('end_time')
                            ->label(__('schedule-resource.form.end_time'))
                            ->required()
                            ->after('start_time')
                            ->validationMessages([
                                'after' => __('schedule-resource.validation.end_time_after'),
                            ]),

                        SpatieTagsInput::make('tags')
                            ->label(__('schedule-resource.form.location_type'))
                            ->type(LocationType::key())
                            ->suggestions(LocationType::values())
                            ->required()
                            ->rules(['max:1']),

                        Forms\Components\TextInput::make('location_details')
                            ->label(__('schedule-resource.form.location_details'))
                            ->placeholder(__('schedule-resource.form.location_details_placeholder'))
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make(__('schedule-resource.form.section.assignment_status'))
                    ->schema([
                        Forms\Components\Select::make('assigned_teacher_id')
                            ->label(__('schedule-resource.form.assigned_teacher'))
                            ->relationship('assignedTeacher', 'name')
                            ->searchable()
                            ->preload(),

                        Forms\Components\Select::make('status')
                            ->label(__('schedule-resource.form.status'))
                            ->options(Status::getOptions())
                            ->required(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('schedule-resource.form.title')) // Re-use form label
                    ->searchable()
                    ->limit(30)
                    ->tooltip(fn(?string $state): ?string => $state),
                Tables\Columns\TextColumn::make('schedulable')
                    ->label(__('schedule-resource.table.associated_with'))
                    ->getStateUsing(fn($record): ?string => app(ScheduleRepositoryInterface::class)->getSchedulableTitle($record))
                    ->searchable(
                        condition: ['name', 'title'],
                        isIndividual: true
                    )
                    ->limit(30)
                    ->tooltip(fn(?string $state): ?string => $state),
                Tables\Columns\TextColumn::make('assignedTeacher.name')
                    ->label(__('schedule-resource.form.assigned_teacher')) // Re-use form label
                    ->searchable(isIndividual: true)
                    ->limit(30)
                    ->tooltip(fn(?string $state): ?string => $state),
                Tables\Columns\TextColumn::make('start_time')
                    ->label(__('schedule-resource.form.start_time')) // Re-use form label
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_time')
                    ->label(__('schedule-resource.form.end_time')) // Re-use form label
                    ->dateTime()
                    ->sortable(),
                SpatieTagsColumn::make('tags')
                    ->label(__('schedule-resource.table.location_type'))
                    ->type(LocationType::key()),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('schedule-resource.form.status')) // Re-use form label
                    ->badge(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('tags')
                    ->label(__('schedule-resource.filters.location_type'))
                    ->multiple()
                    ->searchable()
                    ->options(LocationType::options())
                    ->query(fn(Builder $q, array $data): Builder => app(ScheduleRepositoryInterface::class)
                        ->applyTagFilter($q, $data['values'] ?? [])),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ExportBulkAction::make()
                        ->exporter(ScheduleExporter::class),
                ]),
            ])
            ->headerActions([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSchedules::route('/'),
            'create' => Pages\CreateSchedule::route('/create'),
            'edit' => Pages\EditSchedule::route('/{record}/edit'),
        ];
    }
}
