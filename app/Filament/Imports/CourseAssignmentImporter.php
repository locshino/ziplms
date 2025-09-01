<?php

namespace App\Filament\Imports;

use App\Models\CourseAssignment;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class CourseAssignmentImporter extends Importer
{
    protected static ?string $model = CourseAssignment::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('course')
                ->requiredMapping()
                ->relationship()
                ->rules(['required', 'exists:courses,id']),
            ImportColumn::make('assignment')
                ->requiredMapping()
                ->relationship()
                ->rules(['required', 'exists:assignments,id']),
            ImportColumn::make('start_at')
                ->rules(['date_format:Y-m-d H:i:s']),
            ImportColumn::make('end_submission_at')
                ->rules(['date_format:Y-m-d H:i:s']),
            ImportColumn::make('start_grading_at')
                ->rules(['date_format:Y-m-d H:i:s']),
            ImportColumn::make('end_at')
                ->rules(['date_format:Y-m-d H:i:s']),
        ];
    }

    public function resolveRecord(): CourseAssignment
    {
        return new CourseAssignment;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your course assignment import has completed and '.Number::format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }
}
