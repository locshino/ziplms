<?php

namespace App\Filament\Resources;

use App\Filament\Exports\LectureExporter;
use App\Filament\Resources\LectureResource\Pages;
use App\Filament\Resources\LectureResource\RelationManagers;
use App\Models\Lecture;
use App\States\Status;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use FilamentTiptapEditor\TiptapEditor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LectureResource extends Resource
{
    protected static ?string $model = Lecture::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?int $navigationSort = 1;

    public static function getModelLabel(): string
    {
        return __('lecture-resource.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('lecture-resource.model_label_plural');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('lecture-resource.navigation.group');
    }

    public static function form(Form $form): Form
    {
        $statusOptions = collect(Status::getStates())
            ->mapWithKeys(function ($stateClass) {
                $stateInstance = new $stateClass(new Lecture);

                return [$stateInstance::$name => $stateInstance->getLabel()];
            })
            ->all();

        return $form
            ->schema([
                Forms\Components\Grid::make()->columns(3)->schema([
                    Forms\Components\Group::make()->columnSpan(2)->schema([
                        Forms\Components\Section::make(__('lecture-resource.form.section.main'))
                            ->schema([
                                Forms\Components\TextInput::make('title')->required()->maxLength(255)->label(__('lecture-resource.form.title')),
                                TiptapEditor::make('description')
                                    ->label(__('lecture-resource.form.description'))
                                    ->default(['type' => 'doc', 'content' => []])
                                    ->columnSpanFull(),
                            ]),
                    ]),
                    Forms\Components\Group::make()->columnSpan(1)->schema([
                        Forms\Components\Section::make(__('lecture-resource.form.section.meta'))
                            ->schema([
                                Forms\Components\Select::make('course_id')->relationship('course', 'name')->searchable()->preload()->required()->label(__('lecture-resource.form.course')),
                                Forms\Components\TextInput::make('duration_estimate')
                                    ->label(__('lecture-resource.form.duration_estimate'))
                                    ->mask('99:99')->placeholder('00:00')
                                    ->rules(['regex:/^([0-3][0-9]|4[0-8]):[0-5][0-9]$/'])
                                    ->formatStateUsing(function (?string $state): ?string {
                                        if (empty($state)) {
                                            return null;
                                        }
                                        preg_match_all('/\d+/', $state, $matches);
                                        $numbers = $matches[0];
                                        $hours = 0;
                                        $minutes = 0;
                                        if (str_contains($state, 'hour')) {
                                            $hours = (int) ($numbers[0] ?? 0);
                                            $minutes = (int) ($numbers[1] ?? 0);
                                        } else {
                                            $minutes = (int) ($numbers[0] ?? 0);
                                        }

                                        return sprintf('%02d:%02d', $hours, $minutes);
                                    })
                                    ->dehydrateStateUsing(function (?string $state): ?string {
                                        if (empty($state)) {
                                            return null;
                                        }
                                        $parts = explode(':', $state);
                                        $hours = (int) ($parts[0] ?? 0);
                                        $minutes = (int) ($parts[1] ?? 0);
                                        $displayParts = [];
                                        if ($hours > 0) {
                                            $displayParts[] = "{$hours} ".__('lecture-resource.time.hours');
                                        }
                                        if ($minutes > 0) {
                                            $displayParts[] = "{$minutes} ".__('lecture-resource.time.minutes');
                                        }

                                        return count($displayParts) > 0 ? implode(' ', $displayParts) : null;
                                    }),
                                Forms\Components\TextInput::make('lecture_order')->required()->numeric()->default(0)->label(__('lecture-resource.form.lecture_order')),
                                Forms\Components\Select::make('status')
                                    ->options($statusOptions)
                                    ->required()
                                    ->default((new Status::$defaultStateClass(new Lecture))::$name)
                                    ->label(__('lecture-resource.form.status')),
                            ]),
                    ]),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        $statusFilterOptions = collect(Status::getStates())
            ->mapWithKeys(function ($stateClass) {
                $stateInstance = new $stateClass(new Lecture);

                return [$stateInstance::$name => $stateInstance->getLabel()];
            })
            ->all();

        return $table
            ->reorderable('lecture_order')
            ->columns([
                Tables\Columns\TextColumn::make('lecture_order')->label(__('lecture-resource.table.order'))->toggleable(isToggledHiddenByDefault: true)->sortable(),
                Tables\Columns\TextColumn::make('title')->label(__('lecture-resource.table.title'))->searchable()->limit(30),
                Tables\Columns\TextColumn::make('course.name')->label(__('lecture-resource.table.course'))->searchable()->sortable()->limit(30),
                Tables\Columns\TextColumn::make('duration_estimate')
                    ->label(__('lecture-resource.table.duration_estimate'))
                    ->formatStateUsing(fn (?string $state): string => $state ?? '-')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('status')->label(__('lecture-resource.table.status'))->badge(),
                Tables\Columns\TextColumn::make('created_at')->label(__('lecture-resource.table.created_at'))->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('course')->relationship('course', 'name')->label(__('lecture-resource.filters.course')),
                Tables\Filters\SelectFilter::make('status')->options($statusFilterOptions)->label(__('lecture-resource.filters.status')),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\ExportBulkAction::make()->exporter(LectureExporter::class)->label(__('lecture-resource.actions.export_selected')),
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Grid::make(3)->schema([
                    Group::make()->columnSpan(2)->schema([
                        Section::make(__('lecture-resource.infolist.section.main'))
                            ->schema([
                                TextEntry::make('title')->label(__('lecture-resource.infolist.title')),
                                TextEntry::make('description')->html()->label(__('lecture-resource.infolist.description'))->columnSpanFull(),
                            ]),
                        Section::make(__('lecture-resource.infolist.section.statistics'))
                            ->schema([
                                TextEntry::make('enrolled_users')
                                    ->label(__('lecture-resource.infolist.enrolled_users'))
                                    ->state(fn (Lecture $record): int => $record->users()->count()),

                                TextEntry::make('completed_users')
                                    ->label(__('lecture-resource.infolist.completed_users'))
                                    ->state(fn (Lecture $record): int => $record->users()->wherePivot('status', 'completed')->count()),
                            ])->columns(2),
                    ]),
                    Group::make()->columnSpan(1)->schema([
                        Section::make(__('lecture-resource.infolist.section.meta'))
                            ->schema([
                                TextEntry::make('course.name')->label(__('lecture-resource.infolist.course')),
                                TextEntry::make('duration_estimate')
                                    ->label(__('lecture-resource.infolist.duration_estimate'))
                                    ->formatStateUsing(fn (?string $state): string => $state ?? '-'),
                                TextEntry::make('lecture_order')->label(__('lecture-resource.infolist.lecture_order')),
                                TextEntry::make('status')->label(__('lecture-resource.infolist.status'))->badge(),
                                // Khối Actions đã được xóa ở đây
                            ]),
                    ]),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\UsersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLectures::route('/'),
            'create' => Pages\CreateLecture::route('/create'),
            'view' => Pages\ViewLecture::route('/{record}'),
            'edit' => Pages\EditLecture::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
