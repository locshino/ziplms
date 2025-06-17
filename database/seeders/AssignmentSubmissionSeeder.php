<?php

namespace Database\Seeders;

use App\Enums\AssignmentType;
use App\Models\AssignmentSubmission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class AssignmentSubmissionSeeder extends Seeder
{
    use Concerns\HasSampleFiles;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AssignmentSubmission::factory()
            ->count(20)
            ->create()
            ->each(function (AssignmentSubmission $submission) {
                $sampleFiles = $this->getSampleFiles();
                if (empty($sampleFiles)) {
                    $this->command->warn("No sample files available to attach to submission ID: {$submission->id}.");

                    return;
                }

                // Thêm ngẫu nhiên 1 file cho mỗi submission
                $selectedFile = collect($sampleFiles)->random();

                try {
                    $submission
                        ->addMediaFromUrl($selectedFile['url'])
                        ->preservingOriginal()
                        ->setName($selectedFile['name'])
                        ->toMediaCollection(AssignmentType::key());
                } catch (\Exception $e) {
                    Log::error("Failed to add media from URL {$selectedFile['url']} for submission ID {$submission->id}: ".$e->getMessage());
                    $this->command->warn("Could not add media from URL: {$selectedFile['url']} for submission ID: {$submission->id}. Error: {$e->getMessage()}");
                }
            });

        $this->command->info('Seeded assignment submissions with attachments successfully.');
    }
}
