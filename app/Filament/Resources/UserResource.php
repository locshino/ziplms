<?php

namespace App\Filament\Resources;

use App\Enums\RoleEnum;
use App\Enums\UserEnum;
use App\Filament\Exports\UserExporter;
use App\Filament\Resources\UserResource\Pages;
use App\Models\Role;
use App\Models\User;
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
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $modelLabel = 'Người dùng';
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
            ->withCount([
                'courses',
                'courses as completed_courses_count' => function (Builder $query) {
                    $query->where('course_enrollments.status', 'completed');
                },
            ])
            ->with(['roles', 'organizations', 'classesMajors'])
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
                        ->revealable()
                        ->rule(Password::min(8)->mixedCase()->numbers())
                        ->dehydrateStateUsing(fn($state) => Hash::make($state))
                        ->dehydrated(fn($state) => filled($state))
                        ->required(fn(string $operation): bool => $operation === 'create')
                        ->visible(fn(string $operation): bool => $operation === 'create'),

                    Forms\Components\TextInput::make('password_confirmation')
                        ->label('Xác nhận mật khẩu')
                        ->password()
                        ->revealable()
                        ->requiredWith('password')
                        ->dehydrated(false)
                        ->same('password')
                        ->visible(fn(string $operation): bool => $operation === 'create'),
                    Forms\Components\TextInput::make('code')
                        ->label('Mã người dùng')
                        ->required()
                        ->maxLength(50)
                        ->unique(ignoreRecord: true),
                    Forms\Components\TextInput::make('phone_number')
                        ->label('Số điện thoại')
                        ->tel()
                        ->maxLength(50),
                    Forms\Components\TextInput::make('address')
                        ->label('Địa chỉ')
                        ->maxLength(255),
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
                                ->where('name', '!=', RoleEnum::Dev->value)
                        )
                        ->preload()
                        ->searchable(),
                    Forms\Components\Select::make('status')
                        ->label('Trạng thái')
                        ->options(
                            collect(Status::getStates())
                                ->mapWithKeys(fn($stateClass) => [$stateClass::$name => $stateClass::label()])
                        )
                        ->required()
                        ->default(\App\States\Active::$name),
                    Forms\Components\Select::make('organizations')
                        ->label('Cơ sở')
                        ->relationship('organizations', 'name')
                        ->multiple()
                        ->preload()
                        ->searchable(),
                    Forms\Components\Select::make('classesMajors')
                        ->label('Lớp')
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
                ->searchable()
                ->limit(15),
            Tables\Columns\TextColumn::make('email')
                ->searchable()
                ->limit(15),
            Tables\Columns\TextColumn::make('organizations.name')
                ->label('Cơ sở')
                ->badge()
                ->limitList(1)
                ->searchable(),
            Tables\Columns\TextColumn::make('classesMajors.name')
                ->label('Lớp')
                ->badge()
                ->searchable(),
            Tables\Columns\TextColumn::make('roles.name')
                ->label('Vai trò')
                ->badge()
                ->color(fn(string $state): string => UserEnum::tryFrom($state)?->color() ?? 'gray'),
            Tables\Columns\TextColumn::make('status')
                ->label('Trạng thái')
                ->badge()
                ->formatStateUsing(fn(Status $state) => $state::label())
                ->color(fn(Status $state) => $state->color()),

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
                ->label('Lớp')
                ->relationship('classesMajors', 'name')
                ->multiple()
                ->preload()
                ->searchable(),
            SelectFilter::make('status')
                ->label('Trạng thái')
                ->options(
                    collect(Status::getStates())
                        ->mapWithKeys(fn($stateClass) => [$stateClass::$name => $stateClass::label()])
                )
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
            BulkActionGroup::make([
                ExportBulkAction::make()
                    ->label('Xuất mục đã chọn')
                    ->exporter(UserExporter::class),
                DeleteBulkAction::make()
                    ->label('Chắc chắn xoá các mục đã chọn?')
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Xóa thành công')
                            ->body('Các người dùng đã được xóa khỏi hệ thống.')
                    ),
            ]),
        ];
    }
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Thông tin người dùng')
                    ->columns(2)
                    ->schema([
                        Infolists\Components\Group::make()
                            ->columnSpan(1)
                            ->schema([
                                SpatieMediaLibraryImageEntry::make('profile_picture')
                                    ->collection('profile_picture')
                                    ->circular()
                                    ->height(120)
                                    ->width(120)
                                    ->alignCenter()
                                    ->columnSpanFull(),
                                TextEntry::make('name')
                                    ->label(false)
                                    ->size('2xl')
                                    ->weight('bold')
                                    ->alignCenter(),
                                TextEntry::make('code')
                                    ->label('Mã người dùng')
                                    ->icon('heroicon-m-identification')
                                    ->badge(),
                                TextEntry::make('email')
                                    ->label('Email')
                                    ->icon('heroicon-m-envelope')
                                    ->copyable(),
                                TextEntry::make('phone_number')
                                    ->label('Số điện thoại')
                                    ->icon('heroicon-m-phone')
                                    ->placeholder('Chưa cập nhật'),
                            ]),
                        Infolists\Components\Group::make()
                            ->columnSpan(1)
                            ->schema([
                                TextEntry::make('address')
                                    ->label('Địa chỉ')
                                    ->icon('heroicon-m-map-pin')
                                    ->placeholder('Chưa cập nhật'),
                                TextEntry::make('roles.name')
                                    ->label('Vai trò')
                                    ->badge()
                                    ->color(fn(string $state): string => UserEnum::tryFrom($state)?->color() ?? 'gray'),
                                TextEntry::make('organizations.name')
                                    ->label('Cơ sở')
                                    ->badge()
                                    ->listWithLineBreaks(),
                                TextEntry::make('classesMajors.name')
                                    ->label('Lớp')
                                    ->badge(),
                            ]),
                    ]),
                Section::make('Thống kê học tập')
                    ->columnSpanFull()
                    ->columns(3)
                    ->schema([
                        TextEntry::make('courses_count')
                            ->label('Số môn học đã đăng ký')
                            ->icon('heroicon-o-academic-cap')
                            ->size(TextEntry\TextEntrySize::Large),
                        TextEntry::make('completed_courses_count')
                            ->label('Số môn học đã hoàn thành')
                            ->icon('heroicon-o-check-circle')
                            ->size(TextEntry\TextEntrySize::Large),
                        TextEntry::make('progress_percentage')
                            ->label('Tiến độ học tập')
                            ->icon('heroicon-o-presentation-chart-line')
                            ->size(TextEntry\TextEntrySize::Large)
                            ->color('primary')
                            ->state(fn($record): string => empty($record->courses_count)
                                ? '0%'
                                : round($record->completed_courses_count / $record->courses_count * 100) . '%'),
                    ]),
                Section::make('Trạng thái & Lịch sử')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextEntry::make('status')
                            ->label('Trạng thái')
                            ->badge()
                            ->formatStateUsing(fn(Status $state) => $state::label())
                            ->color(fn(Status $state) => $state->color()),
                        TextEntry::make('email_verified_at')
                            ->label('Đã xác thực')
                            ->since()
                            ->icon('heroicon-m-check-badge'),
                        TextEntry::make('created_at')
                            ->label('Tham gia')
                            ->since()
                            ->icon('heroicon-m-calendar-days'),
                        TextEntry::make('updated_at')
                            ->label('Cập nhật')
                            ->since()
                            ->icon('heroicon-m-arrow-path'),
                    ]),
            ]);
    }
}