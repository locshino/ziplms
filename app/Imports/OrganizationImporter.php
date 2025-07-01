<?php

namespace App\Imports;

use App\Imports\Base\ExcelImporter;
use App\Imports\Concerns\IsSmallImport;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class OrganizationImporter extends ExcelImporter implements IsSmallImport
{
    public function model(array $row): ?Model
    {
        return new Organization([
            'name' => $row['name'],
            'slug' => Str::slug($row['name']),
            'address' => $row['address'] ?? null,
            'phone_number' => $row['phone_number'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:organizations,name'],
            'address' => ['nullable', 'string', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:20'],
        ];
    }
}