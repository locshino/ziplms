<?php

namespace App\Filament\Resources;

use App\Enums\CourseStaffRole;
use App\Filament\Resources\CourseStaffAssignmentResource\Pages;
use App\Models\CourseStaffAssignment;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rules\Unique;

class CourseStaffAssignmentResource extends Resource
{
    protected static ?string $model = CourseStaffAssignment::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $modelLabel = 'Phân công';

    protected static ?string $pluralModelLabel = 'Phân công Nhân sự';

    protected static ?string $navigationGroup = 'Quản lý Khóa học';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Thông tin phân công chi tiết')
                    ->schema([
                        Forms\Components\Select::make('course_id')
                            ->label('Khóa học')
                            ->relationship('course', 'name')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->required(),

                        Forms\Components\Select::make('user_id')
                            ->label('Nhân sự')
                            ->relationship(
                                name: 'user',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn (Builder $query) => $query->whereHas('roles', fn ($q) => $q->whereIn('name', ['admin', 'manager', 'teacher']))
                            )
                            ->searchable()
                            ->preload()
                            ->required()
                            ->unique(
                                table: CourseStaffAssignment::class,
                                column: 'user_id',
                                ignoreRecord: true,
                                modifyRuleUsing: function (Unique $rule, Get $get) {
                                    return $rule->where('course_id', $get('course_id'));
                                }
                            )
                            ->validationMessages([
                                'unique' => 'Nhân sự này đã được phân công cho khóa học này rồi.',
                            ]),

                        Forms\Components\Select::make('role_tag')
                            ->label('Vai trò trong khóa học')
                            ->options(CourseStaffRole::class)
                            ->required(),

                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('course.name')
                    ->label('Khóa học')
                    ->searchable()
                    ->sortable()
                    ->url(fn ($record): string => CourseResource::getUrl('edit', ['record' => $record->course_id])),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Họ và Tên')
                    ->searchable()
                    ->sortable()
                    ->url(fn ($record): string => UserResource::getUrl('edit', ['record' => $record->user_id])),

                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable(),

                Tables\Columns\TextColumn::make('tags.name')
                    ->label('Vai trò')
                    ->badge(),
            ])
            ->filters([
                SelectFilter::make('course')
                    ->label('Lọc theo khóa học')
                    ->relationship('course', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('role')
                    ->label('Lọc theo vai trò')
                    ->options(CourseStaffRole::class)
                    ->query(function (Builder $query, array $data): Builder {
                        if (empty($data['value'])) {
                            return $query;
                        }

                        return $query->whereHas('tags', function ($query) use ($data) {
                            $query->where('name', $data['value']);
                        });
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->after(function (CourseStaffAssignment $record, array $data) {
                        if (isset($data['role_tag'])) {
                            $record->syncTags([$data['role_tag']]);
                        }
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->groups([
                Group::make('course_id')
                    ->label('Khóa học')
                    ->getTitleFromRecordUsing(fn (CourseStaffAssignment $record): ?string => $record->course?->name),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCourseStaffAssignments::route('/'),
            'create' => Pages\CreateCourseStaffAssignment::route('/create'),
            'edit' => Pages\EditCourseStaffAssignment::route('/{record}/edit'),
        ];
    }
}
