<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssignmentGradeResource\Pages;
use App\Models\AssignmentGrade;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AssignmentGradeResource extends Resource
{
    use Translatable;

    protected static ?string $model = AssignmentGrade::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('grade')
                    ->label(__('assignment_grade.form.grade'))
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(10)
                    ->required(),
                Textarea::make('feedback')
                    ->label(__('assignment_grade.form.feedback'))
                    ->rows(5),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('submission.user.name')->label(__('assignment_grade.fields.submission_user_name')),
                TextColumn::make('submission.assignment.title')->label(__('assignment_grade.fields.submission_assignment_title')),
                TextColumn::make('grade')->label(__('assignment_grade.fields.grade')),
                TextColumn::make('feedback')->label(__('assignment_grade.fields.feedback'))->limit(30),
                TextColumn::make('updated_at')->label(__('assignment_grade.fields.updated_at'))->dateTime(),
            ])
            ->filters([
                SelectFilter::make('submission.assignment.course.organization.classesMajors')
                    ->label('Lọc theo Lớp học')
                    ->relationship('submission.assignment.course.organization.classesMajors', 'name')
                    ->placeholder('Chọn lớp học'),
                SelectFilter::make('submission.assignment.course')
                    ->label('Lọc theo môn học')
                    ->relationship('submission.assignment.course', 'name')
                    ->placeholder('Chọn môn học'),

            ])
            ->actions([

                Tables\Actions\EditAction::make()
                    ->label('Chấm điểm')
                    ->icon('heroicon-o-pencil-square'),

                Action::make('download')
                    ->label('Tải bài nộp')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn($record) => asset('storage/' . $record->submission->submission_text))
                    ->openUrlInNewTab(),

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

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $query->select('assignment_grades.*')
            ->joinSub(
                DB::table('assignment_submissions as sub2')
                    ->selectRaw('MAX(id) as latest_id')
                    ->groupBy('user_id', 'assignment_id'),
                'latest_subs',
                'assignment_grades.submission_id',
                '=',
                'latest_subs.latest_id'
            );
        if (Auth::user()->hasRole('teacher')) {
            $teacherId = Auth::id();

            $query->whereHas('submission.assignment', function (Builder $query) use ($teacherId) {
                $query->where('created_by', $teacherId);
            });
        }
        return $query;
    }

    public static function getNavigationLabel(): string
    {
        return __('assignment_grade.label.plural');
    }

    protected function getTitle(): string
    {
        return __('assignment_grade.label.plural');
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-pencil-square';
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAssignmentGrades::route('/'),
            'create' => Pages\CreateAssignmentGrade::route('/create'),
            'edit' => Pages\EditAssignmentGrade::route('/{record}/edit'),
        ];
    }
}
