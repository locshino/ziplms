<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseEnrollmentResource\Pages;
use App\Models\CourseEnrollment;
use App\States\Course\CourseStatus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;


class CourseEnrollmentResource extends Resource
{
    use Translatable;

    protected static ?string $model = CourseEnrollment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    public static function getModelLabel(): string
    {
        return __('course-enrollment-resource.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('course-enrollment-resource.model_label_plural');
    }

    public static function form(Form $form): Form
    {

        return $form
            ->schema([
                Forms\Components\Select::make('course_id')
                    ->label(__('course-enrollment-resource.form.course_id'))
                    ->relationship('course', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('user_id')
                    ->label(__('course-enrollment-resource.form.user_id'))
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\DatePicker::make('enrollment_date')
                    ->label(__('course-enrollment-resource.form.enrollment_date'))
                    ->default(now())
                    ->required(),

                Forms\Components\Select::make('status')
                    ->label(__('course-enrollment-resource.form.status'))
                    ->options(CourseStatus::getOptions())
                    ->native(false)
                    ->required(),

                Forms\Components\TextInput::make('final_grade')
                    ->label(__('course-enrollment-resource.form.final_grade'))
                    ->numeric()
                    ->nullable(),
                Forms\Components\DatePicker::make('completed_at')
                    ->label(__('course-enrollment-resource.form.completed_at'))
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('course.name')
                    ->label(__('course-enrollment-resource.table.course'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('course-enrollment-resource.table.user'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label(__('course-enrollment-resource.table.status'))
                    ->badge(),

                Tables\Columns\TextColumn::make('enrollment_date')
                    ->label(__('course-enrollment-resource.table.enrollment_date'))
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('final_grade')
                    ->label(__('course-enrollment-resource.table.final_grade'))
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('course')
                    ->relationship('course', 'name')
                    ->label(__('course-enrollment-resource.filters.course')),

                Tables\Filters\SelectFilter::make('status')
                    ->label(__('course-enrollment-resource.filters.status'))
                    ->options(CourseStatus::getOptions())
                    ->native(false),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('course-enrollment-resource.notifications.delete_success_title'))
                            ->body(__('course-enrollment-resource.notifications.delete_success_body')),
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListCourseEnrollments::route('/'),
            'create' => Pages\CreateCourseEnrollment::route('/create'),
            'edit' => Pages\EditCourseEnrollment::route('/{record}/edit'),
        ];
    }
}