<?php

namespace Database\Seeders;

use App\Models\UserImportBatch;
use Illuminate\Database\Seeder;

class UserImportBatchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserImportBatch::factory()->count(3)->create();
    }
}
