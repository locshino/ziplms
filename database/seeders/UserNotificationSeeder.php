<?php

namespace Database\Seeders;

use App\Models\UserNotification;
use Illuminate\Database\Seeder;

class UserNotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserNotification::factory()->count(30)->create();
    }
}
