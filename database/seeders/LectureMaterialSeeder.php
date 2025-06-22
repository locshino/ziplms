<?php

namespace Database\Seeders;

use App\Enums\AttachmentType;
use App\Models\LectureMaterial;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

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
                $sampleFiles = $this->getSampleFiles();
                $numberOfSampleFiles = count($sampleFiles);
                if ($numberOfSampleFiles < 2) {
                    $this->command->warn(
                        "Not enough sample files available to attach to material ID: {$material->id}. ".
                            "Expected at least 2, found {$numberOfSampleFiles}."
                    );

                    return;
                }

                // Thêm ngẫu nhiên từ 1 đến 2 file cho mỗi material
                $maxOfFilesEachMaterial = min(2, $numberOfSampleFiles);
                $numberOfFilesEachMaterial = rand(1, $maxOfFilesEachMaterial);
                $selectedFiles = collect($sampleFiles)->shuffle()->take($numberOfFilesEachMaterial);

                foreach ($selectedFiles as $fileInfo) {
                    try {
                        $material
                            ->addMediaFromUrl($fileInfo['url'])
                            ->preservingOriginal() // Giữ tên file gốc nếu có thể
                            ->setName($fileInfo['name']) // Đặt tên media item
                            ->toMediaCollection(AttachmentType::key());
                    } catch (\Exception $e) {
                        // Ghi log lỗi nếu không tải được file
                        Log::error("Failed to add media from URL {$fileInfo['url']}: ".$e->getMessage());
                        $this->command->warn("Could not add media from URL: {$fileInfo['url']} for material ID: {$material->id}. Error: {$e->getMessage()}");
                    }
                }
            });

        $this->command->info('Seeded lecture materials with attachments successfully.');
    }
}
