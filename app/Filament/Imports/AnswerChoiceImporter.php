<?php

namespace App\Filament\Imports;

use App\Models\AnswerChoice;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class AnswerChoiceImporter extends Importer
{
    protected static ?string $model = AnswerChoice::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('question_id')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer', 'exists:questions,id']),
            ImportColumn::make('choice_text')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),
            ImportColumn::make('is_correct')
                ->requiredMapping()
                ->boolean()
                ->rules(['required', 'boolean']),
        ];
    }

    public function resolveRecord(): ?AnswerChoice
    {
        return AnswerChoice::firstOrNew([
            'question_id' => $this->data['question_id'],
            'choice_text' => $this->data['choice_text'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your answer choice import has completed and '.number_format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }
}
