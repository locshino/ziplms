<?php

namespace Database\Seeders;

use App\Enums\AssignmentType;
use App\Models\AssignmentSubmission;
use Illuminate\Database\Seeder;

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
                $this->attachSampleFiles(
                    $submission,
                    AssignmentType::key(),
                    ['count' => 1] // Attach exactly one file
                );
            });

        $this->command->info('Seeded assignment submissions with attachments successfully.');
    }
}
