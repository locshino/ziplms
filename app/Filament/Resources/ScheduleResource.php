<?php

namespace App\Filament\Resources;

use App\Enums\LocationType;
use App\Enums\SchedulableType;
use App\Filament\Exports\ScheduleExporter;
use App\Filament\Imports\ScheduleImporter;
use App\Filament\Resources\ScheduleResource\Pages;
use App\Models\Schedule;
use App\States\Status;
use Filament\Forms;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ImportAction;
use Filament\Tables\Columns\SpatieTagsColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Tags\Tag;

class ScheduleResource extends Resource
{
    use Translatable;

    protected static ?string $model = Schedule::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $modelLabel = 'Lịch học';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\MorphToSelect::make('schedulable')
                            ->label('Associated With') // Related to
                            ->types(
                                collect(SchedulableType::cases())
                                    ->map(
                                        fn (SchedulableType $type) => Forms\Components\MorphToSelect\Type::make($type->getModelClass())
                                            ->titleAttribute('name')
                                    )
                                    ->all()
                            )
                            ->searchable()
                            ->required(),

                        Forms\Components\TextInput::make('title')
                            ->label('Title')
                            ->required(),
                        Forms\Components\RichEditor::make('description')
                            ->label('Description')
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Time & Location')
                    ->schema([
                        Forms\Components\DateTimePicker::make('start_time')
                            ->label('Start Time')
                            ->required(),

                        Forms\Components\DateTimePicker::make('end_time')
                            ->label('End Time')
                            ->required(),

                        SpatieTagsInput::make('tags')
                            ->label('Location Type')
                            ->type(LocationType::key()) // Use the key from your enum
                            ->suggestions(LocationType::values()) // Provide suggestions from the enum
                            ->maxItems(1) // Ensure only one location type can be selected
                            ->required(),

                        Forms\Components\TextInput::make('location_details')
                            ->label('Location Details (Room, URL, etc.)')
                            ->placeholder('e.g., Room A1, https://zoom.us/j/...')
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Assignment & Status')
                    ->schema([
                        Forms\Components\Select::make('assigned_teacher_id')
                            ->label('Assigned Teacher')
                            ->relationship('assignedTeacher', 'name')
                            ->searchable()
                            ->preload(),

                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                            ])
                            ->default('active')
                            ->required(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('schedulable.name')
                    ->label('Associated With')
                    ->searchable(),
                Tables\Columns\TextColumn::make('assignedTeacher.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_time')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_time')
                    ->dateTime()
                    ->sortable(),
                SpatieTagsColumn::make('tags')
                    ->label('Location Type')
                    ->type(LocationType::key()),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (Status $state): string => $state->color()),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tags')
                    ->label('Filter by Location Type')
                    ->multiple()
                    ->searchable()
                    // 1. Manually get the options for the dropdown
                    ->options(LocationType::options())
                    // 2. Define how to apply the filter to the main query
                    ->query(function (Builder $query, array $data): Builder {
                        // $data['values'] contains an array of selected tag names
                        if (empty($data['values'])) {
                            return $query;
                        }

                        // Use the scope provided by spatie/laravel-tags
                        return $query->withAnyTags($data['values'], LocationType::key());
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    // If you use pxlrbt/filament-excel
                    Tables\Actions\ExportBulkAction::make()
                        ->exporter(ScheduleExporter::class),
                ]),
            ])
            ->headerActions([
                Tables\Actions\ExportAction::make()
                    ->exporter(ScheduleExporter::class),
                ImportAction::make()
                    ->importer(ScheduleImporter::class),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Add relation managers here if needed, e.g., for Attendance
        ];
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
