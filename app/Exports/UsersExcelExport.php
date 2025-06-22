<?php

namespace App\Exports;

use App\Exports\Base\ExcelExport;
use pxlrbt\FilamentExcel\Columns\Column;

class UsersExcelExport extends ExcelExport
{
    /**
     * This method is called automatically to set up the export.
     */
    public function setUp(): void
    {
        // Call parent setup to apply default queueing and chunking.
        parent::setUp();

        // Define columns using a declarative and readable approach.
        $this->withColumns([
            Column::make('id')->heading('ID'),
            Column::make('name')->heading('Name'),
            Column::make('email')->heading('Email'),
            Column::make('created_at')->heading('Created At'),
        ]);
    }
}
