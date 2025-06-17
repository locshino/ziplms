<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Enums\AssignmentType;
use Illuminate\Database\Seeder;

class AssignmentSeeder extends Seeder
{
    use Concerns\HasEnumTags;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Assignment::factory()
            ->count(10)
            ->create()
            ->each(function (Assignment $assignment) {
                $this->assignRandomTagFromEnum(AssignmentType::class, $assignment);
            });
    }
}
