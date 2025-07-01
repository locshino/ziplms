<?php

namespace App\Filament\Resources;

use App\Enums\RoleEnum;
use App\Models\User;
use App\Models\Role;
use App\Exports\UsersExcelExport;
use App\Filament\Actions\ExportExcelBulkAction;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Forms\Components\Grid as FormGroup;
use Filament\Forms\Components\Section as FormSection;
use Filament\Infolists\Components\Grid as InfolistGrid;
use Filament\Infolists\Components\Section as InfolistSection;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\TextEntry;
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
            'view' => Pages\ViewUser::route('/{record}/view'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereDoesntHave('roles', fn(Builder $query) => $query
                ->where('name', RoleEnum::Admin->value));
    }

    public static function getFormSchema(): array
    {
        return [
            FormSection::make('Thông tin người dùng')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Họ và tên')
                        ->required()
                        ->minLength(3)
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
                        ->required(fn(string $operation): bool => $operation === 'create')
                        ->rule(Password::defaults())
                        ->confirmed(),
                    Forms\Components\TextInput::make('password_confirmation')
                        ->label('Xác nhận mật khẩu')
                        ->password()
                        ->requiredWith('password')
                        ->dehydrated(false),
                    SpatieMediaLibraryFileUpload::make('profile_picture')
                        ->label('Ảnh')
                        ->collection('profile_picture')
                        ->image()
                        ->maxSize(2048)
                        ->columnSpanFull(),
                ])->columns(2),

            FormSection::make('Vai trò, Tổ chức & Lớp học')
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
                        ->searchable()
                        ->disabled(function ($livewire): bool {
                            return $livewire instanceof Pages\CreateUser && filled($livewire->role);
                        })
                        ->dehydrated(false),
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
            Tables\Columns\TextColumn::make('code')
                ->label('Mã')
                ->sortable()
                ->default('Null')
                ->color(fn($state): string => $state === 'Null' ? 'gray' : 'primary')
                ->searchable(),
            Tables\Columns\TextColumn::make('name')
                ->label('Tên')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('email')
                ->searchable(),
            Tables\Columns\TextColumn::make('organizations.name')
                ->label('Cơ sở')
                ->badge()
                ->limitList(1)
                ->searchable(),
            Tables\Columns\TextColumn::make('classesMajors.name')
                ->label('Lớp / Chuyên ngành')
                ->badge()
                ->searchable(),
            Tables\Columns\TextColumn::make('roles.name')
                ->label('Vai trò')
                ->badge()
                ->color(fn(string $state): string => RoleEnum::tryFrom($state)?->color() ?? 'gray'),
            Tables\Columns\TextColumn::make('status')
                ->label('Trạng thái')
                ->badge()
                ->color(fn(string $state): string => User::getStatusColor($state))
                ->sortable()
                ->searchable(),
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
            SelectFilter::make('status')
                ->label('Trạng thái')
                ->options(User::getStatusOptions())
                ->multiple()
                ->preload(),
        ];
    }

    public static function getTableActions(): array
    {
        return [
            Tables\Actions\ViewAction::make()->label('Xem'),
            Tables\Actions\EditAction::make()->label('Sửa'),
            Tables\Actions\DeleteAction::make()->label('Xóa')
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Xóa thành công')
                        ->body('Người dùng đã được xóa khỏi hệ thống.')
                ),
        ];
    }

    public static function getTableBulkActions(): array
    {
        return [
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make()
                    ->before(function (Collection $records, Tables\Actions\DeleteBulkAction $action) {
                        $user = Auth::user();

                        if (!$user instanceof User) {
                            return;
                        }

                        if ($records->contains(fn(User $record) => !$user->can('delete', $record))) {
                            Notification::make()->title('Không thể xóa')->body('Một hoặc nhiều người dùng được chọn không thể bị xóa.')->danger()->send();
                            $action->halt();
                        }
                    })
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Xóa hàng loạt thành công')
                            ->body('Các người dùng được chọn đã được xóa khỏi hệ thống.')
                    ),
            ]),
            ExportExcelBulkAction::make()
                ->exports([
                    UsersExcelExport::make()->withFilename('Users Export - ' . now()->format('Y-m-d')),
                ]),
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                InfolistGrid::make(3)->schema([

                    InfolistSection::make('Thông tin chính')
                        ->schema([
                            SpatieMediaLibraryImageEntry::make('profile_picture')
                                ->label('')
                                ->collection('profile_picture')
                                ->circular()
                                ->alignCenter()
                                ->height(150) 
                                ->columnSpanFull(),
                            TextEntry::make('name')
                                ->label('Họ và tên')
                                ->icon('heroicon-o-user')
                                ->size(TextEntry\TextEntrySize::Large)
                                ->weight('bold'),
                            TextEntry::make('email')
                                ->label('Email')
                                ->icon('heroicon-o-envelope')
                                ->copyable(),
                            TextEntry::make('phone_number')
                                ->label('Số điện thoại')
                                ->icon('heroicon-o-phone')
                                ->placeholder('Chưa cập nhật'),
                            TextEntry::make('code')
                                ->label('Mã người dùng')
                                ->placeholder('Chưa cập nhật'),
                            TextEntry::make('address')
                                ->label('Địa chỉ')
                                ->icon('heroicon-o-map-pin')
                                ->placeholder('Chưa cập nhật')
                                ->columnSpanFull(),
                        ])->columns(2)
                        ->columnSpan(2),

                    Infolists\Components\Group::make()
                        ->schema([
                            InfolistSection::make('Phân loại & Vai trò')
                                ->schema([
                                    TextEntry::make('roles.name')
                                        ->label('Vai trò')
                                        ->badge(),
                                    TextEntry::make('organizations.name')
                                        ->label('Cơ sở')
                                        ->badge()
                                        ->listWithLineBreaks(),
                                    TextEntry::make('classesMajors.name')
                                        ->label('Lớp / Chuyên ngành')
                                        ->badge()
                                        ->listWithLineBreaks(),
                                ])->collapsible(),
                            InfolistSection::make('Trạng thái & Lịch sử')
                                ->schema([
                                    TextEntry::make('status')
                                        ->label('Trạng thái')
                                        ->badge()
                                        ->color(fn(string $state): string => static::$model::getStatusColor($state)),
                                    TextEntry::make('email_verified_at')
                                        ->label('Ngày xác thực email')
                                        ->dateTime('d/m/Y H:i:s')
                                        ->placeholder('Chưa xác thực'),
                                    TextEntry::make('created_at')
                                        ->label('Ngày tạo')
                                        ->dateTime('d/m/Y H:i:s'),
                                    TextEntry::make('updated_at')
                                        ->label('Cập nhật lần cuối')
                                        ->since(),
                                ])->collapsible(),
                        ])->columnSpan(1),
                                ])
            ]);
    }
}