<?php

namespace App\Console\Commands;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Console\Command;

class ClearSeederCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seeder:clear-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all seeder cache data to force regeneration on next seed';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $seeder = new DatabaseSeeder();
        $seeder->setCommand($this);
        $seeder->clearCache();

        return Command::SUCCESS;
    }
}