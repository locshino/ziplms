<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentQuizAnswerResource\Pages;
use App\Models\StudentQuizAnswer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use HayderHatem\FilamentExcelImport\Actions\Concerns\CanImportExcelRecords;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class StudentQuizAnswerResource extends Resource
{
    use CanImportExcelRecords;

    protected static ?string $model = StudentQuizAnswer::class;

    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';

    protected static ?string $navigationGroup = 'Quản lý';

    protected static ?int $navigationSort = 14;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('quiz_attempt_id')
                    ->label(__('student_quiz_answer_resource.fields.quiz_attempt_id'))
                    ->relationship('quizAttempt', 'id')
                    ->required(),
                Forms\Components\Select::make('question_id')
                    ->label(__('student_quiz_answer_resource.fields.question_id'))
                    ->relationship('question', 'title')
                    ->required(),
                Forms\Components\Select::make('answer_choice_id')
                    ->label(__('student_quiz_answer_resource.fields.answer_choice_id'))
                    ->relationship('answerChoice', 'title')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label(__('student_quiz_answer_resource.columns.id'))
                    ->searchable()
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('quizAttempt.id')
                    ->label(__('student_quiz_answer_resource.columns.quiz_attempt_id'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('question.title')
                    ->label(__('student_quiz_answer_resource.columns.question_title'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('answerChoice.title')
                    ->label(__('student_quiz_answer_resource.columns.answer_choice_title'))
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListStudentQuizAnswers::route('/'),
            'create' => Pages\CreateStudentQuizAnswer::route('/create'),
            'view' => Pages\ViewStudentQuizAnswer::route('/{record}'),
            'edit' => Pages\EditStudentQuizAnswer::route('/{record}/edit'),
        ];
    }
}
