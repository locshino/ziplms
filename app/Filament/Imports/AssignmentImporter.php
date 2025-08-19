<?php

namespace App\Filament\Imports;

use App\Models\Assignment;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class AssignmentImporter extends Importer
{
    protected static ?string $model = Assignment::class;

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
            ImportColumn::make('due_date')
                ->rules(['nullable', 'date']),
            ImportColumn::make('max_score')
                ->rules(['nullable', 'numeric', 'min:0']),
        ];
    }

    public function resolveRecord(): ?Assignment
    {
        return Assignment::firstOrNew([
            // Update existing records, matching them by title and course_id
            'title' => $this->data['title'],
            'course_id' => $this->data['course_id'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your assignment import has completed and '.number_format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }
}
