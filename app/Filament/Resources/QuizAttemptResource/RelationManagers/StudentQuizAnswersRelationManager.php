<?php

namespace App\Filament\Resources\QuizAttemptResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class StudentQuizAnswersRelationManager extends RelationManager
{
    protected static string $relationship = 'answers';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('question_id')
                    ->label('Câu hỏi')
                    ->relationship('question', 'title')
                    ->required(),
                Forms\Components\Select::make('answer_choice_id')
                    ->label('Lựa chọn')
                    ->relationship('answerChoice', 'title')
                    ->required(),
                Forms\Components\Textarea::make('answer_text')
                    ->label('Câu trả lời văn bản')
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_correct')
                    ->label('Đúng')
                    ->disabled(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('question.title')
            ->columns([
                Tables\Columns\TextColumn::make('question.title')
                    ->label('Câu hỏi')
                    ->limit(50),
                Tables\Columns\TextColumn::make('answerChoice.title')
                    ->label('Lựa chọn')
                    ->limit(30),
                Tables\Columns\TextColumn::make('answer_text')
                    ->label('Câu trả lời')
                    ->limit(50),
                Tables\Columns\IconColumn::make('is_correct')
                    ->label('Đúng')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_correct')
                    ->label('Đáp án đúng'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
