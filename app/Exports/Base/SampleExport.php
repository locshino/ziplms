<?php

namespace App\Exports\Base;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

/**
 * Abstract base class for sample data exports.
 * Concrete classes must implement the `headings()` method to define their specific column headers.
 */
abstract class SampleExport implements FromCollection, WithHeadings
{
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function collection(): Collection
    {
        return new Collection($this->data);
    }

    /**
     * Defines the column headings for the export.
     * This method must be implemented by concrete classes.
     */
    abstract public function headings(): array;
}
