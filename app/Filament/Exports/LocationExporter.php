<?php

namespace App\Filament\Exports;

use App\Models\Location;
use Filament\Actions\Exports\ExportColumn;

class LocationExporter extends Base\Exporter
{
    protected static ?string $model = Location::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('name_en')
                ->label('Name (English)')
                ->getStateUsing(fn ($record) => $record->getTranslation('name', 'en')),
            ExportColumn::make('name_vi')
                ->label('Name (Vietnamese)')
                ->getStateUsing(fn ($record) => $record->getTranslation('name', 'vi')),
            ExportColumn::make('address_en')
                ->label('Address (English)')
                ->getStateUsing(fn ($record) => $record->getTranslation('address', 'en')),
            ExportColumn::make('address_vi')
                ->label('Address (Vietnamese)')
                ->getStateUsing(fn ($record) => $record->getTranslation('address', 'vi')),
            ExportColumn::make('latitude')
                ->label('Latitude')
                ->getStateUsing(fn ($record) => $record->locate['lat'] ?? null),
            ExportColumn::make('longitude')
                ->label('Longitude')
                ->getStateUsing(fn ($record) => $record->locate['lng'] ?? null),
            ExportColumn::make('status')
                ->label('Status'),
            ExportColumn::make('created_at')
                ->label('Created At'),
            ExportColumn::make('updated_at')
                ->label('Updated At'),
        ];
    }
}
