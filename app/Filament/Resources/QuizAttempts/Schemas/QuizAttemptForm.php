<?php

namespace App\Filament\Resources\QuizAttempts\Schemas;

use App\Enums\Status\QuizAttemptStatus;
use App\Filament\Resources\Quizzes\Tables\QuizzesTable;
use App\Filament\Resources\Users\Tables\UsersTable;
use App\Livewire\ShowQuizAnswers;
use App\Models\QuizAttempt;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\ModalTableSelect;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Novadaemon\FilamentPrettyJson\Form\PrettyJsonField;

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
                Select::make('status')
                    ->options(QuizAttemptStatus::class)
                    ->required(),
                DateTimePicker::make('start_at'),
                DateTimePicker::make('end_at'),

                // PrettyJsonField::make('answers')
                //     ->columnSpanFull(),

                Section::make('Answers')
                    ->columnSpanFull()
                    ->collapsed()
                    ->lazy() // This is the key for lazy loading
                    ->schema([
                        // The Livewire component will only be mounted when the Accordion is opened
                        Livewire::make(ShowQuizAnswers::class)
                            ->key(fn (?QuizAttempt $record) => $record?->id),
                    ]),
            ]);
    }
}
