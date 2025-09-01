<?php

namespace App\Filament\Imports;

use App\Enums\Status\AssignmentStatus;
use App\Models\Assignment;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Filament\Forms\Components\Select;
use Illuminate\Support\Number;

class AssignmentImporter extends Importer
{
    protected static ?string $model = Assignment::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('title')
                ->example('Homework 1')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('description')
                ->example('This is a sample assignment description.'),
            ImportColumn::make('max_points')
                ->example('10')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('max_attempts')
                ->example('3')
                ->numeric()
                ->rules(['integer']),
        ];
    }

    public static function getOptionsFormComponents(): array
    {
        return [
            Select::make('default_status')
                ->options(AssignmentStatus::class)
                ->required()
                ->helperText('The status to assign to the imported courses.'),
        ];
    }

    public function resolveRecord(): Assignment
    {
        $assignment = new Assignment;
        $assignment->status = $this->options['default_status'] ?? AssignmentStatus::DRAFT->value;

        return $assignment;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your assignment import has completed and '.Number::format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }

    public function getValidationMessages(): array
    {
        return [
            'title.required' => 'The title field is required.',
            'max_points.required' => 'The max_points field is required.',
            'max_points.integer' => 'The max_points field must be an integer.',
            'max_attempts.integer' => 'The max_attempts field must be an integer.',
        ];
    }
}
