<?php

namespace Database\Seeders;

use App\Enums\OrganizationType;
use App\Models\Organization;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    use Concerns\HasEnumTags;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Organization::factory()
            ->count(3)
            ->create()
            ->each(function (Organization $organization) {
                $this->assignRandomTagFromEnum(OrganizationType::class, $organization);
            });
    }
}
