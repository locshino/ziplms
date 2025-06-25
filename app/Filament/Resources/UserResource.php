<?php

namespace App\Filament\Resources;

use App\Enums\RoleEnum;
use App\Models\User;
use App\Models\ClassesMajor;
use App\Models\Role;
use App\Exports\UsersExcelExport;
use App\Filament\Actions\ExportExcelBulkAction;
use App\Filament\Resources\UserResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(static::getFormSchema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(static::getTableColumns())
            ->filters(static::getTableFilters())
            ->actions(static::getTableActions())
            ->bulkActions(static::getTableBulkActions());
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

        /**
     * Modify the base Eloquent query for the resource.
     * This method is used to filter records displayed in the table.
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereDoesntHave('roles', fn (Builder $query) => $query
            ->where('name', RoleEnum::Admin->value));
    }

    public static function getFormSchema(): array
    {
        return [
            Section::make('Thông tin người dùng')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Họ và tên')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('email')
                        ->label('Địa chỉ Email')
                        ->email()
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true),
                    Forms\Components\TextInput::make('password')
                        ->label('Mật khẩu')
                        ->password()
                        ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
                        ->dehydrated(fn(?string $state): bool => filled($state))
                        ->required(fn(string $operation): bool => $operation === 'create'), // Removed ->singleFile()
                        SpatieMediaLibraryFileUpload::make('profile_picture')
                        ->label('Ảnh')
                        ->collection('profile_picture'),
                ])->columns(2),

            Section::make('Vai trò, Tổ chức & Lớp học')
                ->schema([
                    Forms\Components\Select::make('roles')
                        ->label('Vai trò')
                        ->relationship(
                            name: 'roles',
                            titleAttribute: 'name',
                            modifyQueryUsing: fn(Builder $query) => $query
                        ->where('name', '!=', RoleEnum::Admin->value)
                        )
                        ->preload()
                        ->searchable(),
                    Forms\Components\Select::make('organizations')
                        ->label('Cơ sở')
                        ->relationship('organizations', 'name')
                        ->multiple()
                        ->preload()
                        ->searchable(),
                    Forms\Components\Select::make('classesMajors')
                        ->label('Lớp / Chuyên ngành')
                        ->relationship('classesMajors', 'name')
                        ->preload()
                        ->searchable(),
                ])->columns(2),
        ];
    }

    public static function getTableColumns(): array
    {
        return [
            SpatieMediaLibraryImageColumn::make('profile_picture')
                ->label('Ảnh')
                ->collection('profile_picture')
                ->circular(),
            Tables\Columns\TextColumn::make('name')
                ->label('Tên')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('classesMajors.name')
                ->label('Lớp / Chuyên ngành')
                ->badge()
                ->searchable(),
            Tables\Columns\TextColumn::make('email')
                ->searchable(),
            Tables\Columns\TextColumn::make('organizations.name')
                ->label('Cơ sở')
                ->badge()
                ->limitList(1)
                ->searchable(),
            Tables\Columns\TextColumn::make('roles.name')
                ->label('Vai trò')
                ->badge()
                ->color(fn(string $state): string => RoleEnum::tryFrom($state)?->color() ?? 'gray'),
            Tables\Columns\TextColumn::make('created_at')
                ->label('Ngày tạo')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    public static function getTableFilters(): array
    {
        return [
            SelectFilter::make('roles')
                ->label('Vai trò')
                ->relationship('roles', 'name')
                ->options(fn() => Role::where('name', '!=', RoleEnum::Admin->value)->pluck('name', 'id'))
                ->multiple()
                ->preload(),
            SelectFilter::make('organizations')
                ->label('Cơ sở')
                ->relationship('organizations', 'name')
                ->multiple()
                ->preload()
                ->searchable(),
            SelectFilter::make('classesMajors')
                ->label('Lớp / Chuyên ngành')
                ->relationship('classesMajors', 'name')
                ->multiple()
                ->preload()
                ->searchable(),
        ];
    }

    public static function getTableActions(): array
    {
        return [
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ];
    }

    public static function getTableBulkActions(): array
    {
        return [
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make()
                    ->before(function (Collection $records, 
                    Tables\Actions\DeleteBulkAction $action) {
                        $user = Auth::user();

                            if (!$user instanceof User) {
                                return;
                            }
                            
                        if ($records->contains(fn(User $record) => !$user->can('delete', $record))) {
                            Notification::make()->title('Không thể xóa')->body('Một hoặc nhiều người dùng được chọn không thể bị xóa.')->danger()->send();
                            $action->halt();
                        }
                    }),
            ]),
            ExportExcelBulkAction::make()
                ->exports([
                    UsersExcelExport::make()->withFilename('Users Export - ' . now()->format('Y-m-d')),
                ]),
        ];
    }
}