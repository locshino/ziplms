<?php

namespace App\Filament\Imports;

use App\Models\BadgeCondition;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class BadgeConditionImporter extends Importer
{
    protected static ?string $model = BadgeCondition::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('badge_id')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer', 'exists:badges,id']),
            ImportColumn::make('condition_type')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),
            ImportColumn::make('condition_value')
                ->requiredMapping()
                ->rules(['required', 'string']),
            ImportColumn::make('target_value')
                ->numeric()
                ->rules(['nullable', 'integer']),
        ];
    }

    public function resolveRecord(): ?BadgeCondition
    {
        return BadgeCondition::firstOrNew([
            'badge_id' => $this->data['badge_id'],
            'condition_type' => $this->data['condition_type'],
            'condition_value' => $this->data['condition_value'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your badge condition import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}