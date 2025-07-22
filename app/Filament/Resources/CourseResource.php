<?php

namespace App\Filament\Resources;

use App\Filament\Exports\CourseExporter;
use App\Filament\Imports\CourseImporter;
use App\Filament\Resources\CourseResource\Pages;
use App\Filament\Resources\CourseResource\RelationManagers\StaffRelationManager;
use App\Models\Course;
use App\States\Status;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ImportAction;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CourseResource extends Resource
{
    use Translatable;

    protected static ?string $model = Course::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getModelLabel(): string
    {
        return __('course-resource.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('course-resource.model_label_plural');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make(__('course-resource.form.section.general'))
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label(__('course-resource.form.name'))
                                    ->required(),
                                Forms\Components\RichEditor::make('description')
                                    ->label(__('course-resource.form.description'))
                                    ->columnSpanFull(),
                                Forms\Components\SpatieTagsInput::make('tags')
                                    ->label(__('course-resource.form.tags')),
                            ])
                            ->columns(2),
                    ])
                    ->columnSpan(['lg' => 2]),
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make(__('course-resource.form.section.config'))
                            ->schema([
                                Forms\Components\SpatieMediaLibraryFileUpload::make('image')
                                    ->label(__('course-resource.form.image'))
                                    ->collection('image'),
                                Forms\Components\TextInput::make('code')
                                    ->label(__('course-resource.form.code'))
                                    ->required()
                                    ->unique(ignoreRecord: true),
                                Forms\Components\Select::make('status')
                                    ->label(__('course-resource.form.status'))
                                    ->options(collect(Status::getStates())->mapWithKeys(fn ($state) => [$state::$name => $state::label()]))
                                    ->required(),
                                Forms\Components\Select::make('parent_id')
                                    ->label(__('course-resource.form.parent_id'))
                                    ->relationship('parent', 'name')
                                    ->searchable()
                                    ->options(fn (?Course $record) => Course::where('id', '!=', $record?->id)->pluck('name', 'id')),
                                Forms\Components\Select::make('organization_id')
                                    ->label(__('course-resource.form.organization_id'))
                                    ->relationship('organization', 'name')
                                    ->searchable()
                                    ->required(),
                            ]),
                        Forms\Components\Section::make(__('course-resource.form.section.time'))
                            ->schema([
                                Forms\Components\DatePicker::make('start_date')
                                    ->label(__('course-resource.form.start_date')),
                                Forms\Components\DatePicker::make('end_date')
                                    ->label(__('course-resource.form.end_date'))
                                    ->afterOrEqual('start_date'),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('image')
                    ->label(__('course-resource.table.image'))->collection('image')->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('course-resource.table.name'))->searchable()->sortable(),
                Tables\Columns\TextColumn::make('code')
                    ->label(__('course-resource.table.code'))->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('course-resource.table.status'))
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state::label())
                    ->color(fn ($state): string => $state->color()),
                Tables\Columns\SpatieTagsColumn::make('tags')
                    ->label(__('course-resource.table.tags')),
                Tables\Columns\TextColumn::make('organization.name')
                    ->label(__('course-resource.table.organization'))->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('course-resource.table.updated_at'))->dateTime('d/m/Y H:i')->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('course-resource.filters.status'))
                    ->options(collect(Status::getStates())->mapWithKeys(fn ($state) => [$state::$name => $state::label()])),

                SelectFilter::make('organization')
                    ->label(__('course-resource.filters.organization'))
                    ->relationship('organization', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('tags')
                    ->label(__('course-resource.filters.tags'))
                    ->relationship('tags', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload(),

                Filter::make('created_at')
                    ->label(__('course-resource.filters.created_at'))
                    ->form([
                        Forms\Components\DatePicker::make('created_from')->label(__('course-resource.filters.created_from')),
                        Forms\Components\DatePicker::make('created_until')->label(__('course-resource.filters.created_until')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $fromDate): Builder => $query->where('created_at', '>=', Carbon::parse($fromDate)->startOfDay()),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $untilDate): Builder => $query->where('created_at', '<=', Carbon::parse($untilDate)->endOfDay()),
                            );
                    }),
            ])
            // ->headerActions([
            //     ExportAction::make()
            //         ->label(__('course-resource.actions.export'))
            //         ->exporter(CourseExporter::class),
            //     ImportAction::make()
            //         ->label(__('course-resource.actions.import'))
            //         ->importer(CourseImporter::class),
            // ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->successNotification(
                        \Filament\Notifications\Notification::make()
                            ->success()
                            ->title(__('course-resource.notifications.delete_success_title'))
                            ->body(__('course-resource.notifications.delete_success_body')),
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            StaffRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'view' => Pages\ViewCourse::route('/{record}'),
            'edit' => Pages\EditCourse::route('/{record}/edit'),
        ];
    }
}