<?php

namespace Database\Seeders;

use App\Models\ImportBatch;
use Illuminate\Database\Seeder;

class ImportBatchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ImportBatch::factory()->count(3)->create();
    }
}
