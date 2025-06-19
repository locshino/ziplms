<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

/**
 * Handles the export of failed validation rows from an import process.
 *
 * This class takes an array of Failure objects from Maatwebsite\Excel,
 * dynamically generates headings based on the original data, and appends
 * a column with the specific validation errors for each failed row.
 */
class FailedRowsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * An array of Failure objects provided by the Maatwebsite\Excel validator.
     *
     * @var \Maatwebsite\Excel\Validators\Failure[]
     */
    protected array $failures;

    /**
     * @param  \Maatwebsite\Excel\Validators\Failure[]  $failures
     */
    public function __construct(array $failures)
    {
        $this->failures = $failures;
    }

    /**
     * Provides the collection of failures to be exported.
     * This is required by the FromCollection concern.
     */
    public function collection(): Collection
    {
        return collect($this->failures);
    }

    /**
     * Dynamically generates the header row for the error report.
     * It uses the keys from the first failed row's data and appends an 'error_reason' column.
     */
    public function headings(): array
    {
        // Get the first failure object to determine the columns.
        $firstRow = $this->failures[0] ?? null;

        if (! $firstRow) {
            return ['Lỗi', 'Lý do']; // A fallback in case of an empty failures array.
        }

        // Get the original column headings from the data.
        $headings = array_keys($firstRow->values());

        // Append a new column for the error messages.
        $headings[] = 'error_reason';

        return $headings;
    }

    /**
     * Maps a single Failure object to a row in the export file.
     *
     * @param  mixed  $failure  A single Failure object.
     */
    public function map($failure): array
    {
        // Get the original row data.
        $rowData = $failure->values();

        // Get all validation errors for this row and join them into a single string.
        $rowData['error_reason'] = implode(', ', $failure->errors());

        return $rowData;
    }
}
