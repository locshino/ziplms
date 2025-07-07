<?php

namespace App\Filament\Exports;

use App\Models\Schedule;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class ScheduleExporter extends Exporter
{
    protected static ?string $model = Schedule::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id'),
            ExportColumn::make('title')
                ->label('Title'),
            ExportColumn::make('description')
                ->label('Description'),
            ExportColumn::make('schedulable.name') // This will get the 'name' attribute from the related model
                ->label('Associated With'),
            ExportColumn::make('schedulable_type')
                ->label('Associated Type'),
            ExportColumn::make('assignedTeacher.name')
                ->label('Assigned Teacher'),
            ExportColumn::make('start_time')
                ->label('Start Time'),
            ExportColumn::make('end_time')
                ->label('End Time'),
            ExportColumn::make('location_details')
                ->label('Location Details'),
            ExportColumn::make('tags.name')
                ->label('Location Type')
                ->listAsJson(),
            ExportColumn::make('status')
                ->label('Status'),
            ExportColumn::make('created_at')
                ->label('Created At'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your schedule export has completed and '.number_format($export->successful_rows).' '.str('row')->plural($export->successful_rows).' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to export.';
        }

        return $body;
    }

    /**
     * Get the name of the queue the job should be sent to.
     */
    public function getJobQueue(): ?string
    {
        return config('worker-queue.batch.name');
    }

    /**
     * Get the name of the connection the job should be sent to.
     */
    public function getJobConnection(): ?string
    {
        return config('worker-queue.batch.connection');
    }

    public function getFormats(): array
    {
        return [
            \Filament\Actions\Exports\Enums\ExportFormat::Csv,
            // \Filament\Actions\Exports\Enums\ExportFormat::Xlsx,
        ];
    }
}
