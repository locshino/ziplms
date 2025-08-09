<?php

namespace App\Filament\Imports;

use App\Models\Role;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class RoleImporter extends Importer
{
    protected static ?string $model = Role::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->requiredMapping()
                ->rules(['required', 'max:255', 'unique:roles,name']),
            ImportColumn::make('guard_name')
                ->rules(['nullable', 'string'])
                ->castStateUsing(function (?string $state): string {
                    return $state ?? 'web';
                }),
        ];
    }

    public function resolveRecord(): ?Role
    {
        return Role::firstOrNew([
            // Update existing records, matching them by name
            'name' => $this->data['name'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your role import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}