<?php

namespace App\Filament\Imports;

use App\Models\QuizQuestion;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class QuizQuestionImporter extends Importer
{
    protected static ?string $model = QuizQuestion::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('quiz')
                ->requiredMapping()
                ->relationship()
                ->rules(['required']),
            ImportColumn::make('question')
                ->requiredMapping()
                ->relationship()
                ->rules(['required']),
            ImportColumn::make('points')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
        ];
    }

    public function resolveRecord(): QuizQuestion
    {
        return new QuizQuestion;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your quiz question import has completed and '.Number::format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }
}
