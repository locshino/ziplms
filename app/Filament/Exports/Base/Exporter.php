<?php

namespace App\Filament\Exports\Base;

use Filament\Actions\Exports\Exporter as FilamentExporter;
use Filament\Actions\Exports\Models\Export;

abstract class Exporter extends FilamentExporter
{
    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your location export has completed and '.number_format($export->successful_rows).' '.str('row')->plural($export->successful_rows).' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to export.';
        }

        return $body;
    }

    public function getJobQueue(): ?string
    {
        return config('worker-queue.exporter.name');
    }

    public function getJobConnection(): ?string
    {
        return config('worker-queue.exporter.connection');
    }

    public function getFormats(): array
    {
        return [
            \Filament\Actions\Exports\Enums\ExportFormat::Csv,
            \Filament\Actions\Exports\Enums\ExportFormat::Xlsx,
        ];
    }
}
