<?php

namespace App\Imports;

use App\Models\ClassesMajor;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ClassMajorImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new ClassesMajor([
            'organization_id' => $row['organization_id'],
            'name' => $row['name'],
            'code' => $row['code'],
            'description' => $row['description'],
            'parent_id' => $row['parent_id'],
        ]);
    }
}
