<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

$delete_records_older_than_hours = config('telescope.delete_records_older_than_hours');
Schedule::command('telescope:prune --hours='.$delete_records_older_than_hours)->daily();

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
