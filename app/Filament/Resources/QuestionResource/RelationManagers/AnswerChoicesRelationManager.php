<?php

namespace App\Filament\Resources\QuestionResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class AnswerChoicesRelationManager extends RelationManager
{
    protected static string $relationship = 'answerChoices';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Lựa chọn')
                    ->required(),
                Forms\Components\Toggle::make('is_correct')
                    ->label('Đáp án đúng')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Lựa chọn'),
                Tables\Columns\IconColumn::make('is_correct')
                    ->label('Đáp án đúng')
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
