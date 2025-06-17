<?php

namespace Database\Seeders;

use App\Enums\EnrollmentType;
use App\Models\UserClassMajorEnrollment;
use Illuminate\Database\Seeder;

class UserClassMajorEnrollmentSeeder extends Seeder
{
    use Concerns\HasEnumTags;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserClassMajorEnrollment::factory()
            ->count(20)
            ->create()
            ->each(function (UserClassMajorEnrollment $enrollment) {
                $this->assignRandomTagFromEnum(EnrollmentType::class, $enrollment);
            });
    }
}
