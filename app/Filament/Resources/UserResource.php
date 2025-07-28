<?php

namespace App\Filament\Resources;

use App\Enums\RoleEnum;
use App\Filament\Exports\UserExporter;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use App\States\Active;
use App\States\Inactive;
use App\States\Status;
use Filament\Forms;
use Filament\Forms\Components\Section as FormSection;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use FilamentTiptapEditor\TiptapEditor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\HtmlString;
use Illuminate\Validation\Rules\Password;

class UserResource extends Resource
{
    use Translatable;

    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function getModelLabel(): string
    {
        return __('user-resource.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('user-resource.model_label_plural');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(static::getFormSchema());
    }

    public static function getFormSchema(): array
    {
        return [
            FormSection::make(__('user-resource.form.section.main'))
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label(__('user-resource.form.name'))
                        ->required()
                        ->minLength(3)
                        ->maxLength(255),
                    Forms\Components\TextInput::make('email')
                        ->label(__('user-resource.form.email'))
                        ->email()
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true),
                    Forms\Components\TextInput::make('password')
                        ->label(__('user-resource.form.password'))
                        ->password()
                        ->revealable()
                        ->rule(Password::min(8)->mixedCase()->numbers())
                        ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                        ->dehydrated(fn ($state) => filled($state))
                        ->required(fn (string $operation): bool => $operation === 'create')
                        ->visible(fn (string $operation): bool => $operation === 'create'),
                    Forms\Components\TextInput::make('password_confirmation')
                        ->label(__('user-resource.form.password_confirmation'))
                        ->password()
                        ->revealable()
                        ->requiredWith('password')
                        ->dehydrated(false)
                        ->same('password')
                        ->visible(fn (string $operation): bool => $operation === 'create'),
                    Forms\Components\TextInput::make('code')
                        ->label(__('user-resource.form.code'))
                        ->required()
                        ->maxLength(50)
                        ->unique(ignoreRecord: true),
                    Forms\Components\TextInput::make('phone_number')
                        ->label(__('user-resource.form.phone_number'))
                        ->tel()
                        ->maxLength(50),
                    TiptapEditor::make('address')
                        ->label(__('user-resource.form.address'))
                        ->columnSpanFull(),
                    SpatieMediaLibraryFileUpload::make('profile_picture')
                        ->label(__('user-resource.form.profile_picture'))
                        ->collection('profile_picture')
                        ->image()
                        ->maxSize(2048)
                        ->columnSpanFull(),
                ])->columns(2),
            FormSection::make(__('user-resource.form.section.roles_permissions'))
                ->schema([
                    Forms\Components\Select::make('roles')
                        ->label(__('user-resource.form.roles'))
                        ->relationship(
                            name: 'roles',
                            titleAttribute: 'name',
                            modifyQueryUsing: fn (Builder $query) => $query
                                ->where('name', '!=', RoleEnum::Admin->value)
                                ->where('name', '!=', RoleEnum::Dev->value)
                        )
                        ->preload()
                        ->searchable(),
                    Forms\Components\Select::make('status')
                        ->label(__('user-resource.form.status'))
                        ->options([
                            Active::$name => (new Active(new User))->label(),
                            Inactive::$name => (new Inactive(new User))->label(),
                        ])
                        ->required()
                        ->default(Active::$name),
                    Forms\Components\Select::make('organizations')
                        ->label(__('user-resource.form.organizations'))
                        ->relationship('organizations', 'name')
                        ->multiple()
                        ->preload()
                        ->searchable(),
                    Forms\Components\Select::make('classesMajors')
                        ->label(__('user-resource.form.classes_majors'))
                        ->relationship('classesMajors', 'name')
                        ->preload()
                        ->searchable(),
                ])->columns(2),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(static::getTableColumns())
            ->filters(static::getTableFilters())
            ->actions(static::getTableActions())
            ->bulkActions(static::getTableBulkActions());
    }

    public static function getTableColumns(): array
    {
        return [
            SpatieMediaLibraryImageColumn::make('profile_picture')
                ->label(__('user-resource.table.profile_picture'))
                ->collection('profile_picture')
                ->circular(),
            Tables\Columns\TextColumn::make('code')
                ->label(__('user-resource.table.code'))
                ->sortable()
                ->default(__('user-resource.table.null_text'))
                ->color(fn ($state): string => $state === __('user-resource.table.null_text') ? 'gray' : 'primary')
                ->searchable(),
            Tables\Columns\TextColumn::make('name')
                ->label(__('user-resource.table.name'))
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('email')
                ->searchable(),
            Tables\Columns\TextColumn::make('organizations.name')
                ->label(__('user-resource.table.organizations'))
                ->badge()
                ->searchable(),
            Tables\Columns\TextColumn::make('classesMajors.name')
                ->label(__('user-resource.table.classes_majors'))
                ->badge()
                ->searchable(),
            Tables\Columns\TextColumn::make('roles.name')
                ->label(__('user-resource.table.roles'))
                ->badge(),
            Tables\Columns\TextColumn::make('status')
                ->label(__('user-resource.table.status'))
                ->badge()
                ->formatStateUsing(fn (Status $state) => $state::label())
                ->color(fn (Status $state) => $state->color()),
            Tables\Columns\TextColumn::make('created_at')
                ->label(__('user-resource.table.created_at'))
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    public static function getTableFilters(): array
    {
        return [
            SelectFilter::make('roles')
                ->label(__('user-resource.filters.roles'))
                ->relationship(
                    'roles',
                    'name',
                    fn (Builder $query) => $query->whereNotIn('name', [
                        RoleEnum::Admin->value,
                        RoleEnum::Dev->value,
                    ])
                )
                ->multiple()
                ->preload(),
            SelectFilter::make('organizations')
                ->label(__('user-resource.filters.organizations'))
                ->relationship('organizations', 'name')
                ->multiple()
                ->preload()
                ->searchable(),
            SelectFilter::make('classesMajors')
                ->label(__('user-resource.filters.classes_majors'))
                ->relationship('classesMajors', 'name')
                ->multiple()
                ->preload()
                ->searchable(),
            SelectFilter::make('status')
                ->label(__('user-resource.filters.status'))
                ->options([
                    Active::$name => (new Active(new User))->label(),
                    Inactive::$name => (new Inactive(new User))->label(),
                ])
                ->multiple()
                ->preload(),
        ];
    }

    public static function getTableActions(): array
    {
        return [
            Tables\Actions\ViewAction::make()->label(__('user-resource.actions.view')),
            Tables\Actions\EditAction::make()->label(__('user-resource.actions.edit')),
            Tables\Actions\DeleteAction::make()->label(__('user-resource.actions.delete'))
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('user-resource.actions.delete_notification_title'))
                        ->body(__('user-resource.actions.delete_notification_body'))
                ),
        ];
    }

    public static function getTableBulkActions(): array
    {
        return [
            BulkActionGroup::make([
                ExportBulkAction::make()
                    ->label(__('user-resource.actions.export_selected'))
                    ->exporter(UserExporter::class),
                DeleteBulkAction::make()
                    ->label(__('user-resource.actions.bulk_delete_confirmation'))
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('user-resource.actions.bulk_delete_success_title'))
                            ->body(__('user-resource.actions.bulk_delete_success_body'))
                    ),
            ]),
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make(__('user-resource.form.section.main'))
                    ->columns(2)
                    ->schema([
                        Infolists\Components\Group::make()
                            ->columnSpan(1)
                            ->schema([
                                SpatieMediaLibraryImageEntry::make('profile_picture')
                                    ->collection('profile_picture')
                                    ->circular()->height(120)->width(120)->alignCenter()->columnSpanFull(),
                                TextEntry::make('name')
                                    ->label(false)->size('2xl')->weight('bold')->alignCenter(),
                                TextEntry::make('code')
                                    ->label(__('user-resource.form.code'))
                                    ->icon('heroicon-m-identification')->badge(),
                                TextEntry::make('email')
                                    ->label(__('user-resource.form.email'))
                                    ->icon('heroicon-m-envelope')->copyable(),
                                TextEntry::make('phone_number')
                                    ->label(__('user-resource.form.phone_number'))
                                    ->icon('heroicon-m-phone')
                                    ->placeholder(__('user-resource.infolist.not_updated')),
                            ]),
                        Infolists\Components\Group::make()
                            ->columnSpan(1)
                            ->schema([
                                TextEntry::make('address')
                                    ->label(__('user-resource.form.address'))
                                    ->html()
                                    ->icon('heroicon-m-map-pin')
                                    ->placeholder(__('user-resource.infolist.not_updated')),
                                TextEntry::make('roles.name')
                                    ->label(__('user-resource.form.roles'))->badge(),
                                TextEntry::make('organizations.name')
                                    ->label(__('user-resource.form.organizations'))
                                    ->badge()->listWithLineBreaks(),
                                TextEntry::make('classesMajors.name')
                                    ->label(__('user-resource.form.classes_majors'))->badge(),
                            ]),
                    ]),
                Section::make(__('user-resource.infolist.progress_and_stats_section_title'))
                    ->columns(3)
                    ->schema([
                        TextEntry::make('lecture_progress')
                            ->label(__('user-resource.infolist.lecture_completion'))
                            ->columnSpanFull()
                            ->html()
                            ->formatStateUsing(function (User $record): HtmlString {
                                $totalLectures = $record->lectures()->count();
                                if ($totalLectures === 0) {
                                    $html = '<div class="text-xl font-bold text-gray-900 dark:text-white mt-1">0%</div>';

                                    return new HtmlString($html);
                                }

                                $completedLectures = $record->lectures()->wherePivot('status', 'completed')->count();
                                $progress = round(($completedLectures / $totalLectures) * 100);

                                $html = '<div class="flex items-center gap-x-3 mt-1">
                                    <div class="flex-1 bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                                        <div class="bg-primary-600 h-2.5 rounded-full" style="width: '.$progress.'%"></div>
                                    </div>
                                    <span class="text-lg font-bold text-gray-900 dark:text-white">'.$progress.'%</span>
                                </div>';

                                return new HtmlString($html);
                            }),

                        TextEntry::make('courses_count')
                            ->label(__('user-resource.infolist.registered_courses'))
                            ->icon('heroicon-o-academic-cap')->size(TextEntry\TextEntrySize::Large),
                        TextEntry::make('completed_courses_count')
                            ->label(__('user-resource.infolist.completed_courses'))
                            ->icon('heroicon-o-check-circle')->size(TextEntry\TextEntrySize::Large),
                        TextEntry::make('progress_percentage')
                            ->label(__('user-resource.infolist.learning_progress'))
                            ->icon('heroicon-o-presentation-chart-line')->size(TextEntry\TextEntrySize::Large)->color('primary')
                            ->state(fn ($record): string => empty($record->courses_count)
                                ? '0%'
                                : round($record->completed_courses_count / $record->courses_count * 100).'%'),
                    ]),
                Section::make(__('user-resource.form.section.status_history'))
                    ->columnSpanFull()->columns(2)
                    ->schema([
                        TextEntry::make('status')
                            ->label(__('user-resource.form.status'))
                            ->badge()
                            ->formatStateUsing(fn (Status $state) => $state::label())
                            ->color(fn (Status $state) => $state->color()),
                        TextEntry::make('email_verified_at')
                            ->label(__('user-resource.infolist.verified_at'))
                            ->placeholder(__('user-resource.infolist.not_verified'))
                            ->since()->icon('heroicon-m-check-badge'),
                        TextEntry::make('created_at')
                            ->label(__('user-resource.infolist.joined_at'))
                            ->since()->icon('heroicon-m-calendar-days'),
                        TextEntry::make('updated_at')
                            ->label(__('user-resource.infolist.last_updated'))
                            ->since()->icon('heroicon-m-arrow-path'),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\LecturesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}/view'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withCount([
                'courses',
                'courses as completed_courses_count' => function (Builder $query) {
                    $query->where('course_enrollments.status', 'completed');
                },
            ])
            ->with(['roles', 'organizations', 'classesMajors'])
            ->whereDoesntHave('roles', fn (Builder $query) => $query
                ->where('name', RoleEnum::Admin->value));
    }
}
