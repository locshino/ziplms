<?php

namespace Database\Seeders;

use App\Models\NewsletterSubscriber;
use Illuminate\Database\Seeder;

class NewsletterSubscriberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        NewsletterSubscriber::factory()->count(25)->create();
    }
}
