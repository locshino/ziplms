<?php

namespace App\Filament\Imports;

use App\Models\Question;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class QuestionImporter extends Importer
{
    protected static ?string $model = Question::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('quiz_id')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer', 'exists:quizzes,id']),
            ImportColumn::make('question_text')
                ->requiredMapping()
                ->rules(['required', 'string']),
            ImportColumn::make('question_type')
                ->requiredMapping()
                ->rules(['required', 'string', 'in:multiple_choice,true_false,short_answer']),
            ImportColumn::make('points')
                ->numeric()
                ->rules(['nullable', 'integer', 'min:0']),
        ];
    }

    public function resolveRecord(): ?Question
    {
        return Question::firstOrNew([
            'quiz_id' => $this->data['quiz_id'],
            'question_text' => $this->data['question_text'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your question import has completed and '.number_format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }
}
