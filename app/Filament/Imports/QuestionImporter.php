<?php

namespace App\Filament\Imports;

use App\Enums\Status\QuestionStatus;
use App\Models\Question;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Filament\Forms\Components\Select;
use Illuminate\Support\Number;

class QuestionImporter extends Importer
{
    protected static ?string $model = Question::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('title')
                ->requiredMapping()
                ->example('What is the capital of Vietnam?')
                ->rules(['required']),
            ImportColumn::make('description')
                ->rules(['nullable']),
        ];
    }

    public function resolveRecord(): Question
    {
        $question = new Question;
        $question->status = $this->options['default_status'] ?? QuestionStatus::PUBLISHED->value;

        return $question;
    }

    public static function getOptionsFormComponents(): array
    {
        return [
            Select::make('default_status')
                ->options(QuestionStatus::class)
                ->required()
                ->helperText('The status to assign to the imported questions.'),
        ];
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your question import has completed and '.Number::format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }
}
