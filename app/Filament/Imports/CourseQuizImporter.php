<?php

namespace App\Filament\Imports;

use App\Models\CourseQuiz;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class CourseQuizImporter extends Importer
{
    protected static ?string $model = CourseQuiz::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('course')
                ->example('123')
                ->requiredMapping()
                ->relationship()
                ->rules(['required']),
            ImportColumn::make('quiz')
                ->example('456')
                ->requiredMapping()
                ->relationship()
                ->rules(['required']),
            ImportColumn::make('start_at')
                ->example(now()->toDateTimeString())
                ->rules(['datetime']),
            ImportColumn::make('end_at')
                ->example(now()->addDay()->toDateTimeString())
                ->rules(['datetime']),
        ];
    }

    public function resolveRecord(): CourseQuiz
    {
        return new CourseQuiz;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your course quiz import has completed and '.Number::format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }
}
