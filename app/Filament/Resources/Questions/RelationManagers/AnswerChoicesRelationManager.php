<?php

namespace App\Filament\Resources\Questions\RelationManagers;

use App\Filament\Resources\AnswerChoices\AnswerChoiceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class AnswerChoicesRelationManager extends RelationManager
{
    protected static string $relationship = 'answerChoices';

    protected static ?string $relatedResource = AnswerChoiceResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make()
                    ->form([
                        \Filament\Forms\Components\TextInput::make('title')
                            ->label('Câu trả lời')
                            ->required(),
                        \Filament\Forms\Components\Textarea::make('description')
                            ->label('Mô tả'),
                        \Filament\Forms\Components\Toggle::make('is_correct')
                            ->label('Đáp án đúng'),
                    ]),
            ]);
    }
}
