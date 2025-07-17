<?php

namespace Database\Seeders;

use App\Enums\AttachmentType;
use App\Models\LectureMaterial;
use Illuminate\Database\Seeder;

class LectureMaterialSeeder extends Seeder
{
    use Concerns\HasSampleFiles;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LectureMaterial::factory()
            ->count(20)
            ->create()
            ->each(function (LectureMaterial $material) {
                $this->attachSampleFiles(
                    $material,
                    AttachmentType::key(),
                    [
                        'min_available' => 2, // Require at least 2 files to be available
                        'count' => [1, 2],    // Attach a random number of files between 1 and 2
                    ]
                );
            });

        $this->command->info('Seeded lecture materials with attachments successfully.');
    }
}
