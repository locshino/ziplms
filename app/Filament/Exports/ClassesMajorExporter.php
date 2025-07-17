<?php

namespace App\Filament\Exports;

use App\Models\ClassesMajor;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class ClassesMajorExporter extends Exporter
{
    protected static ?string $model = ClassesMajor::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')->label('ID'),
            ExportColumn::make('organization_id')->label('Organization ID'),
            ExportColumn::make('name')->label('Name'),
            ExportColumn::make('code')->label('Code'),
            ExportColumn::make('description')->label('Description'),
            ExportColumn::make('parent_id')->label('Parent ID'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your classes major export has completed and '.number_format($export->successful_rows).' '.str('row')->plural($export->successful_rows).' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to export.';
        }

        return $body;
    }
}
