<?php

namespace App\Filament\Imports;

use App\Models\Course;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class CourseImporter extends Importer
{
    protected static ?string $model = Course::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('teacher_id')
                ->requiredMapping()
                ->rules(['required', 'exists:users,id']),
            ImportColumn::make('title')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('description')
                ->rules(['nullable', 'string']),
        ];
    }

    public function resolveRecord(): ?Course
    {
        return Course::firstOrNew([
            // Update existing records, matching them by title and teacher_id
            'title' => $this->data['title'],
            'teacher_id' => $this->data['teacher_id'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your course import has completed and '.number_format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }
}
