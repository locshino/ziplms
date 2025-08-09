<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;

use App\Models\Role;
use App\Models\User;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use HayderHatem\FilamentExcelImport\Actions\Concerns\CanImportExcelRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class UserResource extends Resource
{
    use CanImportExcelRecords;

    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Quản lý';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make(__('user_resource.fields.avatar'))
                    ->description(__('user_resource.sections.update'))
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('avatar') // tên collection media
                            ->collection('avatars')
                            ->label(__('user_resource.fields.avatar'))
                            ->image()
                            ->enableOpen()
                            ->enableDownload(),
                    ])
                    ->collapsible()
                    ->collapsed(),

                Grid::make(2)
                    ->schema([

                        Card::make()
                            ->schema([
                                Section::make(__('user_resource.sections.basic_info'))
                                    ->schema([
                                        TextInput::make('name')
                                            ->label(__('user_resource.fields.name'))
                                            ->required()
                                            ->maxLength(255),

                                        TextInput::make('email')
                                            ->label(__('user_resource.fields.email'))
                                            ->email()
                                            ->required()
                                            ->unique(
                                                table: 'users',
                                                column: 'email',
                                                ignoreRecord: true
                                            ),
                                        TextInput::make('password')
                                            ->label(__('user_resource.fields.password'))
                                            ->password()
                                            ->required()
                                            ->visible(fn(string $context) => $context === 'create')
                                            ->maxLength(255),
                                    ])
                                    ->columns(1),
                            ]),

                        // Vai trò
                        Card::make()
                            ->schema([
                                Section::make(__('user_resource.sections.authorization'))
                                    ->description(__('user_resource.sections.description'))
                                    ->schema([
                                        Select::make('roles')
                                            ->label(__('user_resource.fields.roles'))
                                            ->multiple()
                                            ->options(Role::pluck('name', 'name'))
                                            ->afterStateHydrated(function ($component, $state, $record) {
                                                if ($record && $record->roles) {
                                                    $component->state($record->roles->pluck('name')->toArray());
                                                }
                                            })
                                            ->saveRelationshipsUsing(function ($record, $state) {
                                                $record->syncRoles($state);
                                            }),
                                    ])
                                    ->columns(1),
                            ]),
                    ]),
            ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('avatar')
                    ->label(__('user_resource.columns.avatar'))
                    ->getStateUsing(fn($record) => $record->getFirstMediaUrl('avatars') ?: 'https://jbagy.me/wp-content/uploads/2025/03/hinh-anh-cute-avatar-vo-tri-2.jpg')
                    ->circular()
                    ->size(60),

                Tables\Columns\TextColumn::make('id')
                    ->label(__('user_resource.columns.id'))
                    ->searchable()
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('user_resource.columns.name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label(__('user_resource.columns.email'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->label(__('user_resource.columns.roles'))
                    ->badge(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->label(__('user_resource.columns.email_verified_at'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('user_resource.columns.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('user_resource.columns.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label(__('user_resource.columns.deleted_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                SelectFilter::make('role_id')
                    ->label('Vai trò')
                    ->options(
                        Role::pluck('name', 'id')
                    )
                    ->query(function ($query, $data) {
                        if ($data['value']) {
                            $query->whereHas('roles', function ($q) use ($data) {
                                $q->where('id', $data['value']);
                            });
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn($record) => $record->id !== Auth::id()),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    ExportBulkAction::make()->exports([
                        ExcelExport::make()
                            ->queue()
                            ->askForFilename()
                            ->askForWriterType(),
                    ]),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\UserDetail::route('/{record}/detail'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
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
