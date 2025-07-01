<?php

namespace App\Exports;

use App\Exports\Base\SampleExport;

class OrganizationsSampleExport extends SampleExport
{
    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'name',
            'address',
            'phone_number',
        ];
    }

    public static function sampleData(): array
    {
        return [
            ['name' => 'Cơ sở A', 'address' => '123 Đường ABC, Quận 1, TP.HCM', 'phone_number' => '0901234567'],
            ['name' => 'Cơ sở B', 'address' => '456 Đường XYZ, Quận 2, TP.HCM', 'phone_number' => '0907654321'],
        ];
    }
}