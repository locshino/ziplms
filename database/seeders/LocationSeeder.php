<?php

namespace Database\Seeders;

use App\Enums\LocationType;
use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    use Concerns\HasEnumTags;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Location::factory(20)->create()->each(function (Location $location) {
            $this->assignRandomTagFromEnum(LocationType::class, $location);
        });
    }
}
