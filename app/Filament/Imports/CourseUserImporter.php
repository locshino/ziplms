<?php

namespace App\Filament\Imports;

use App\Models\CourseUser;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class CourseUserImporter extends Importer
{
    protected static ?string $model = CourseUser::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('course')
                ->example('123')
                ->requiredMapping()
                ->relationship()
                ->rules(['required']),
            ImportColumn::make('user')
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

    public function resolveRecord(): CourseUser
    {
        return new CourseUser;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your course user import has completed and '.Number::format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }
}
