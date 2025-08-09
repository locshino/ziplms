<?php

namespace App\Filament\Imports;

use App\Models\StudentQuizAnswer;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class StudentQuizAnswerImporter extends Importer
{
    protected static ?string $model = StudentQuizAnswer::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('quiz_attempt_id')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer', 'exists:quiz_attempts,id']),
            ImportColumn::make('question_id')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer', 'exists:questions,id']),
            ImportColumn::make('answer_choice_id')
                ->numeric()
                ->rules(['nullable', 'integer', 'exists:answer_choices,id']),
            ImportColumn::make('answer_text')
                ->rules(['nullable', 'string']),
            ImportColumn::make('is_correct')
                ->boolean()
                ->rules(['nullable', 'boolean']),
        ];
    }

    public function resolveRecord(): ?StudentQuizAnswer
    {
        return StudentQuizAnswer::firstOrNew([
            'quiz_attempt_id' => $this->data['quiz_attempt_id'],
            'question_id' => $this->data['question_id'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your student quiz answer import has completed and '.number_format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }
}
