<?php

namespace App\Filament\Imports;

use App\Models\AnswerChoice;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class AnswerChoiceImporter extends Importer
{
    protected static ?string $model = AnswerChoice::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('question')
                ->example('123')
                ->requiredMapping()
                ->relationship()
                ->rules(['required', 'exists:questions,id']),
            ImportColumn::make('title')
                ->example('Sample Answer Choice')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('description')
                ->example('This is a sample answer choice.'),
            ImportColumn::make('is_correct')
                ->example('true')
                ->requiredMapping()
                ->boolean()
                ->rules(['required', 'boolean']),
        ];
    }

    public function resolveRecord(): AnswerChoice
    {
        return new AnswerChoice;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your answer choice import has completed and '.Number::format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }

    public function getValidationMessages(): array
    {
        return [
            'question.required' => 'The question field is required.',
            'question.exists' => 'The selected question does not exist.',
            'title.required' => 'The answer choice title is required.',
            'is_correct.required' => 'The is_correct field is required.',
            'is_correct.boolean' => 'The is_correct field must be true or false.',
        ];
    }
}
