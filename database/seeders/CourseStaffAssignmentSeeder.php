<?php

namespace Database\Seeders;

use App\Enums\CourseStaffRoleType;
use App\Models\CourseStaffAssignment;
use Illuminate\Database\Seeder;

class CourseStaffAssignmentSeeder extends Seeder
{
    use Concerns\HasEnumTags;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CourseStaffAssignment::factory()
            ->count(10)
            ->create()
            ->each(function (CourseStaffAssignment $assignment) {
                $this->assignRandomTagFromEnum(CourseStaffRoleType::class, $assignment);
            });
    }
}
