<?php

namespace App\Filament\Imports\Base;

use Filament\Actions\Imports\Importer as FilamentImporter;
use Filament\Actions\Imports\Models\Import;

abstract class Importer extends FilamentImporter
{
    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your location import has completed and '.number_format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }

    public function getJobQueue(): ?string
    {
        return config('worker-queue.batch.name');
    }

    public function getJobConnection(): ?string
    {
        return config('worker-queue.batch.connection');
    }
}
