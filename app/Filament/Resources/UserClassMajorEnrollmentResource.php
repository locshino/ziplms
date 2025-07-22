<?php

namespace App\Filament\Resources;

use App\Filament\Exports\UserClassMajorEnrollmentExporter;
use App\Filament\Resources\UserClassMajorEnrollmentResource\Pages;
use App\Models\Role;
use App\Models\UserClassMajorEnrollment;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

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
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required(),

                Select::make('class_major_id')
                    ->label('Đơn vị cấu trúc')
                    ->relationship('classMajor', 'name')
                    ->searchable()
                    ->required(),

                Select::make('role_id')
                    ->label('Vai trò')
                    ->required()
                    ->options(
                        fn () => Role::query()->select('id', 'name')
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
                    ->label(__('user_class_major_enrollments.columns.id'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('user_class_major_enrollments.columns.user.name'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('classMajor.name')
                    ->label(__('user_class_major_enrollments.columns.classMajor.name'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.role_names_string')
                    ->label(__('user_class_major_enrollments.columns.user.role_names_string'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('start_date')
                    ->label(__('user_class_major_enrollments.columns.start_date'))
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('end_date')
                    ->label(__('user_class_major_enrollments.columns.end_date'))
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('user_class_major_enrollments.columns.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('user_class_major_enrollments.columns.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])->filters([
                SelectFilter::make('class_major_id')
                    ->label('Lọc theo đơn vị cấu trúc')
                    ->options(function () {
                        return app(\App\Repositories\UserClassMajorEnrollmentRepository::class)->getClassMajorFilterOptions();
                    })->query(function (Builder $query, array $data): Builder {
                        return app(\App\Repositories\UserClassMajorEnrollmentRepository::class)->applyClassMajorFilter(
                            $query,
                            $data['value']
                        );
                    }),

            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->using(function ($record) {
                        $user = $record->user;

                        if (! $user) {
                            Notification::make()
                                ->title('Lỗi')
                                ->body('Bản ghi không có người dùng liên kết.')
                                ->danger()
                                ->send();

                            return;
                        }
                        if ($user->id === Auth::id()) {
                            Notification::make()
                                ->title('Không thể xóa')
                                ->body('Bạn không thể xóa bản ghi của chính mình.')
                                ->danger()
                                ->send();

                            return;
                        }
                        $record->forceDelete();
                    }),
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
