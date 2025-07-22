<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrganizationResource\Pages;
use App\Models\Organization;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Illuminate\Database\Eloquent\Builder;

class OrganizationResource extends Resource
{
    protected static ?string $model = Organization::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Tổ chức';
    protected static ?string $pluralLabel = 'Tổ chức';
    protected static ?string $modelLabel = 'Tổ chức';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Thông tin cơ bản')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Tên tổ chức')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\Select::make('type')
                            ->label('Loại hình')
                            ->options([
                                'company' => 'Company',
                                'university' => 'University',
                                'ngo' => 'NGO',
                                'other' => 'Other',
                            ])
                            ->required(),

                        SpatieMediaLibraryFileUpload::make('logo')
                            ->label('Logo')
                            ->disk('public')
                            ->image()
                            ->maxSize(2048)
                            ->imageResizeMode('cover')
                            ->imageResizeTargetWidth('500')
                            ->imageResizeTargetHeight('500')
                            ->collection('logo')
                            ->preserveFilenames(),
                    ]),

                Section::make('Cài đặt & Liên hệ')
                    ->description('Các cài đặt riêng cho tổ chức này.')
                    ->schema(static::getSettingsSchema()),
            ]);
    }

    public static function getSettingsSchema(): array
    {
        return [
            Forms\Components\TextInput::make('settings.address')
                ->label('Địa chỉ'),
            Forms\Components\TextInput::make('settings.phone_number')
                ->label('Số điện thoại')
                ->tel(),
            Forms\Components\TextInput::make('settings.contact_email')
                ->label('Email liên hệ')
                ->email(),
            Forms\Components\TextInput::make('settings.website')
                ->label('Website')
                ->url(),
            Toggle::make('settings.enable_public_courses')
                ->label('Cho phép hiển thị khóa học công khai')
                ->helperText('Nếu bật, các khóa học của tổ chức này có thể được xem bởi người dùng không đăng nhập.'),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('logo')
                    ->label('Logo')
                    ->collection('logo')
                    ->circular(),

                TextColumn::make('name')
                    ->label('Tên tổ chức')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('type')->label('Loại hình'),

                TextColumn::make('settings.phone_number')->label('SĐT')->searchable(),

                TextColumn::make('created_at')->dateTime('d/m/Y')->label('Ngày tạo'),
            ])
            ->filters([
                Filter::make('organization_details')
                    ->form([
                        Forms\Components\TextInput::make('name')
                            ->label('Tên tổ chức'),
                        Forms\Components\TextInput::make('phone_number')
                            ->label('Số điện thoại')
                            ->tel(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['name'],
                                fn(Builder $query, $name): Builder => $query->where('name', 'like', "%{$name}%")
                            )
                            ->when(
                                $data['phone_number'],
                                fn(Builder $query, $phone): Builder => $query->where('settings->phone_number', 'like', "%{$phone}%")
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['name'] ?? null) {
                            $indicators[] = Indicator::make('Tên tổ chức: ' . $data['name'])->removeField('name');
                        }
                        if ($data['phone_number'] ?? null) {
                            $indicators[] = Indicator::make('Số điện thoại: ' . $data['phone_number'])->removeField('phone_number');
                        }
                        return $indicators;
                    }),
                SelectFilter::make('type')
                    ->label('Loại hình')
                    ->options([
                        'company' => 'Company',
                        'university' => 'University',
                        'ngo' => 'NGO',
                        'other' => 'Other',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrganizations::route('/'),
            'create' => Pages\CreateOrganization::route('/create'),
            'edit' => Pages\EditOrganization::route('/{record}/edit'),
        ];
    }
}
