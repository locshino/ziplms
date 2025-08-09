<?php

namespace App\Filament\Imports;

use App\Models\Quiz;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class QuizImporter extends Importer
{
    protected static ?string $model = Quiz::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('course_id')
                ->requiredMapping()
                ->rules(['required', 'exists:courses,id']),
            ImportColumn::make('title')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('description')
                ->rules(['nullable', 'string']),
            ImportColumn::make('time_limit')
                ->rules(['nullable', 'integer', 'min:1']),
            ImportColumn::make('max_attempts')
                ->rules(['nullable', 'integer', 'min:1']),
            ImportColumn::make('passing_score')
                ->rules(['nullable', 'numeric', 'min:0', 'max:100']),
        ];
    }

    public function resolveRecord(): ?Quiz
    {
        return Quiz::firstOrNew([
            // Update existing records, matching them by title and course_id
            'title' => $this->data['title'],
            'course_id' => $this->data['course_id'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your quiz import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}