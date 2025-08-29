<?php

namespace App\Filament\Resources\Submissions\Schemas;

use App\Enums\Status\SubmissionStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SubmissionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('assignment_id')
                    ->label(__('resource_submission.form.fields.assignment_id'))
                    ->relationship('assignment', 'title')
                    ->required(),
                Select::make('student_id')
                    ->label(__('resource_submission.form.fields.student_id'))
                    ->relationship('student', 'name')
                    ->required(),
                Textarea::make('content')
                    ->label(__('resource_submission.form.fields.content'))
                    ->columnSpanFull(),
                Select::make('status')
                    ->label(__('resource_submission.form.fields.status'))
                    ->options(SubmissionStatus::class)
                    ->required(),
                DateTimePicker::make('submitted_at')
                    ->label(__('resource_submission.form.fields.submitted_at')),
                Select::make('graded_by')
                    ->label(__('resource_submission.form.fields.graded_by'))
                    ->relationship('grader', 'name')
                    ->required(),
                TextInput::make('points')
                    ->label(__('resource_submission.form.fields.points'))
                    ->numeric(),
                Textarea::make('feedback')
                    ->label(__('resource_submission.form.fields.feedback'))
                    ->columnSpanFull(),
                DateTimePicker::make('graded_at')
                    ->label(__('resource_submission.form.fields.graded_at')),
            ]);
    }
}
