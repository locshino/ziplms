<?php

namespace App\Filament\Resources\QuizAttempts\Schemas;

use App\Enums\Status\QuizAttemptStatus;
use App\Filament\Resources\Quizzes\Tables\QuizzesTable;
use App\Filament\Resources\Users\Tables\UsersTable;
use App\Models\Quiz;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\ModalTableSelect;
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
                ModalTableSelect::make('quiz_id')
                    ->relationship('quiz', 'title')
                    ->tableConfiguration(QuizzesTable::class)
                    ->required(),
                ModalTableSelect::make('student_id')
                    ->relationship('student', 'name')
                    ->tableConfiguration(UsersTable::class)
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
