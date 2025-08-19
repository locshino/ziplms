<?php

namespace App\Filament\Resources\AnswerChoices\Schemas;

use App\Filament\Resources\Questions\Tables\QuestionsTable;
use Filament\Forms\Components\ModalTableSelect;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AnswerChoiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('question')
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        ModalTableSelect::make('question_id')
                            ->relationship('question', 'title')
                            ->tableConfiguration(QuestionsTable::class)
                            ->required(),
                        Toggle::make('is_multi_choice')
                            ->live(),
                    ]),
                Repeater::make('answer_choices')
                    ->columnSpanFull()
                    ->schema([
                        Textarea::make('title')
                            ->required()
                            ->columnSpanFull(),
                        Textarea::make('description')
                            ->columnSpanFull(),
                        Toggle::make('is_correct')
                            ->reactive()
                            ->fixIndistinctState(fn ($get) => ! ($get('../../is_multi_choice'))),
                    ])
                    ->defaultItems(4),
            ]);
    }
}
