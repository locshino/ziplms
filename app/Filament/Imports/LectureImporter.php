<?php

namespace App\Filament\Imports;

use App\Models\Lecture;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class LectureImporter extends Importer
{
    protected static ?string $model = Lecture::class;

    public static function getColumns(): array
    {
        return [
            //
        ];
    }

    public function resolveRecord(): ?Lecture
    {
        // return Lecture::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new Lecture;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your lecture import has completed and '.number_format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }
}
