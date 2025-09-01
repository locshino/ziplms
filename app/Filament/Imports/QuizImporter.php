<?php

namespace App\Filament\Imports;

use App\Enums\Status\QuizStatus;
use App\Models\Quiz;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Filament\Forms\Components\Select;
use Illuminate\Support\Number;

class QuizImporter extends Importer
{
    protected static ?string $model = Quiz::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('title')
                ->example('General Knowledge Quiz')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('description')
                ->example('A quiz about general knowledge'),
            ImportColumn::make('max_attempts')
                ->example('5')
                ->numeric()
                ->rules(['integer']),
            ImportColumn::make('is_single_session')
                ->example('true')
                ->requiredMapping()
                ->boolean()
                ->rules(['required', 'boolean']),
            ImportColumn::make('time_limit_minutes')
                ->example('30')
                ->numeric()
                ->rules(['integer']),
        ];
    }

    public function resolveRecord(): Quiz
    {
        $quiz = new Quiz;
        $quiz->status = $this->options['default_status'] ?? QuizStatus::PUBLISHED->value;

        return $quiz;
    }

    public static function getOptionsFormComponents(): array
    {
        return [
            Select::make('default_status')
                ->options(QuizStatus::class)
                ->required()
                ->helperText('The status to assign to the imported quizzes.'),
        ];
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your quiz import has completed and '.Number::format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }
}
