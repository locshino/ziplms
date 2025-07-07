<?php

namespace Database\Seeders;

use App\Enums\LocationType;
use App\Models\Schedule;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    use Concerns\HasEnumTags;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schedule::factory()->count(20)->create()
            ->each(function (Schedule $schedule) {
                $this->assignRandomTagFromEnum(LocationType::class, $schedule);
            });
    }
}
