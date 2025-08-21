<?php

namespace App\Filament\Resources\Submissions\Schemas;

use App\Enums\Status\SubmissionStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class SubmissionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('assignment_id')
                    ->relationship('assignment', 'title')
                    ->required(),
                Select::make('student_id')
                    ->relationship('student', 'name')
                    ->required(),
                Textarea::make('content')
                    ->columnSpanFull(),
                Select::make('status')
                    ->options(SubmissionStatus::class)
                    ->required(),
                DateTimePicker::make('submitted_at'),
                Select::make('graded_by')
                    ->relationship('grader', 'name')
                    ->required(),
                TextInput::make('points')
                    ->numeric(),
                Textarea::make('feedback')
                    ->columnSpanFull(),
                DateTimePicker::make('graded_at'),
            ]);
    }
}
