<?php

namespace App\Exports;

use App\Exports\Base\SampleExport;

/**
 * A simple export class for generating a sample import file.
 * It's compatible with packages that expect a standard Maatwebsite\Excel FromCollection export,
 * and now extends a base class for common functionality.
 */
class UsersSampleExport extends SampleExport
{
    public function headings(): array
    {
        // These headings should match the keys in the sampleData() method
        // and ideally align with the columns your UserImporter expects.
        return ['name', 'email', 'created_at'];
    }

    public static function sampleData(): array
    {
        return [
            ['name' => 'John Doe', 'email' => 'john.doe@example.com', 'created_at' => '2023-01-01 12:00:00'],
            ['name' => 'Jane Smith', 'email' => 'jane.smith@example.com', 'created_at' => '2023-01-02 12:00:00'],
            ['name' => 'Alice Johnson', 'email' => 'alice.johnson@example.com', 'created_at' => '2023-01-03 12:00:00'],
        ];
    }
}
