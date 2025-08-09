<?php

namespace App\Filament\Imports;

use App\Models\Enrollment;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class EnrollmentImporter extends Importer
{
    protected static ?string $model = Enrollment::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('user_id')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer', 'exists:users,id']),
            ImportColumn::make('course_id')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer', 'exists:courses,id']),
            ImportColumn::make('enrolled_at')
                ->rules(['nullable', 'date']),
            ImportColumn::make('completed_at')
                ->rules(['nullable', 'date']),
            ImportColumn::make('progress')
                ->numeric()
                ->rules(['nullable', 'numeric', 'min:0', 'max:100']),
        ];
    }

    public function resolveRecord(): ?Enrollment
    {
        return Enrollment::firstOrNew([
            'user_id' => $this->data['user_id'],
            'course_id' => $this->data['course_id'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your enrollment import has completed and '.number_format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }
}
