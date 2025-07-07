<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserClassMajorEnrollmentResource\Pages;
use App\Filament\Resources\UserClassMajorEnrollmentResource\RelationManagers;
use App\Models\UserClassMajorEnrollment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use app\models\User;
use App\Models\ClassesMajor;
use App\Filament\Exports\UserClassMajorEnrollmentExporter;
use Filament\Tables\Actions\ExportAction;
use App\Models\Role;

class UserClassMajorEnrollmentResource extends Resource
{
    
    protected static ?string $model = UserClassMajorEnrollment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
              Select::make('user_id')
                    ->label('Người dùng')
                    ->relationship('user', 'name') // quan hệ model User
                    ->searchable()
                    ->required(),

                Select::make('class_major_id')
                    ->label('Đơn vị cấu trúc')
                    ->relationship('classMajor', 'name') // quan hệ model ClassMajor
                    ->searchable()
                    ->required(),

                Select::make('role_id')
                    ->label('Vai trò')
                    ->required()
                    ->options(
                        fn() => Role::query()->select('id', 'name')
                            ->pluck('name', 'id'),
                    ),

                DatePicker::make('start_date')
                    ->label('Ngày bắt đầu')
                    ->required(),

                DatePicker::make('end_date')
                    ->label('Ngày kết thúc')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                 Tables\Columns\TextColumn::make('id')
        ->label('ID')
        ->searchable(),

    Tables\Columns\TextColumn::make('user.name')
        ->label('Người dùng')
        ->searchable()
        ->sortable(),

    Tables\Columns\TextColumn::make('classMajor.name')
        ->label('Đơn vị cấu trúc')
        ->searchable()
        ->sortable(),

    Tables\Columns\TextColumn::make('user.role_names_string')
        ->label('Vai trò')
        ->sortable(),

    Tables\Columns\TextColumn::make('start_date')
        ->label('Ngày bắt đầu')
        ->date()
        ->sortable(),

    Tables\Columns\TextColumn::make('end_date')
        ->label('Ngày kết thúc')
        ->date()
        ->sortable(),

    Tables\Columns\TextColumn::make('created_at')
        ->label('Tạo lúc')
        ->dateTime()
        ->sortable()
        ->toggleable(isToggledHiddenByDefault: true),

    Tables\Columns\TextColumn::make('updated_at')
        ->label('Cập nhật lúc')
        ->dateTime()
        ->sortable()
        ->toggleable(isToggledHiddenByDefault: true),
            ])->filters([
    SelectFilter::make('class_major_id')
        ->label('Lọc theo đơn vị cấu trúc')
        ->options(function () {
            return \App\Models\ClassesMajor::query()->pluck('name', 'id');
        })->query(function (Builder $query, array $data): Builder {
            if (! empty($data['value'])) {
                $query->where('class_major_id', $data['value']);
            }
            return $query;
        }),
])
->actions([
                Tables\Actions\ViewAction::make(),
            ])
             ->headerActions([
            ExportAction::make()
                ->exporter(UserClassMajorEnrollmentExporter::class),
                

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
            'index' => Pages\ListUserClassMajorEnrollments::route('/'),
            'create' => Pages\CreateUserClassMajorEnrollment::route('/create'),
            'edit' => Pages\EditUserClassMajorEnrollment::route('/{record}/edit'),
        ];
    }
}
