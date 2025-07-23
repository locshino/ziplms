<?php

namespace App\Filament\Resources;

use App\Enums\SchedulableType;
use App\Filament\Exports\ScheduleExporter;
use App\Filament\Resources\ScheduleResource\Pages;
use App\Models\Location;
use App\Models\Schedule;
use App\Repositories\Contracts\ScheduleRepositoryInterface;
use App\States\Status;
use Dvarilek\FilamentTableSelect\Components\Form\TableSelect;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use FilamentTiptapEditor\TiptapEditor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Swis\Filament\Activitylog;

class ScheduleResource extends Resource
{
    use Translatable;

    protected static ?string $model = Schedule::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

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

                        TiptapEditor::make('description')
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
                            ->after('start_time'),

                        // Changed from SpatieTagsInput to a relationship select
                        Forms\Components\Select::make('location_id')
                            ->label(__('schedule-resource.form.location'))
                            ->relationship('location', 'name')
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->required(),
                            ])
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make(__('schedule-resource.form.section.assignment_status'))
                    ->schema([
                        // Changed to assigned_id and relationship assignedTo
                        TableSelect::make('assigned_id')
                            ->label(__('schedule-resource.form.assigned_to'))
                            ->relationship('assignedTo', 'name')
                            ->tableLocation(UserResource::class),

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
                    ->label(__('schedule-resource.form.title'))
                    ->searchable()
                    ->limit(30)
                    ->tooltip(fn (?string $state): ?string => $state),

                Tables\Columns\TextColumn::make('schedulable')
                    ->label(__('schedule-resource.table.associated_with'))
                    ->getStateUsing(fn ($record): ?string => app(ScheduleRepositoryInterface::class)->getSchedulableTitle($record))
                    ->searchable(
                        condition: ['name', 'title'],
                        isIndividual: true
                    )
                    ->limit(30)
                    ->tooltip(fn (?string $state): ?string => $state),

                // Changed to assignedTo.name
                Tables\Columns\TextColumn::make('assignedTo.name')
                    ->label(__('schedule-resource.form.assigned_to'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                // Added location column
                Tables\Columns\TextColumn::make('location.name')
                    ->label(__('schedule-resource.form.location'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('start_time')
                    ->label(__('schedule-resource.form.start_time'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('end_time')
                    ->label(__('schedule-resource.form.end_time'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('status')
                    ->label(__('schedule-resource.form.status'))
                    ->badge()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),

                // Added status filter
                Tables\Filters\SelectFilter::make('status')
                    ->label(__('schedule-resource.form.status'))
                    ->multiple()
                    ->options(Status::getOptions()),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Activitylog\Tables\Actions\ActivitylogAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ExportBulkAction::make()
                        ->exporter(ScheduleExporter::class),
                ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
            'view' => Pages\ViewSchedule::route('/{record}'),
            'edit' => Pages\EditSchedule::route('/{record}/edit'),
        ];
    }
}
