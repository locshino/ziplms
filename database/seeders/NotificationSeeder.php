<?php

namespace Database\Seeders;

use App\Models\Notification; // Or App\Models\Notification if that's the actual model
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Notification::factory()->count(10)->create();
    }
}
