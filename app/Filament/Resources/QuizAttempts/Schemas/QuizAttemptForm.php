<?php

namespace App\Filament\Resources\QuizAttempts\Schemas;

use App\Enums\Status\QuizAttemptStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class QuizAttemptForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('quiz_id')
                    ->relationship('quiz', 'title')
                    ->required(),
                Select::make('student_id')
                    ->relationship('student', 'name')
                    ->required(),
                TextInput::make('points')
                    ->numeric(),
                Textarea::make('answers')
                    ->columnSpanFull(),
                DateTimePicker::make('start_at'),
                DateTimePicker::make('end_at'),
                Select::make('status')
                    ->options(QuizAttemptStatus::class)
                    ->required(),
            ]);
    }
}
