<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Spatie\OneTimePasswords\Models\OneTimePassword as OTPCode;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule the pruning of OTP codes
Schedule::command('model:prune', [
    '--model' => [
        OTPCode::class,
        \App\Models\OTPLogin::class,
    ],
])->daily();

Schedule::command('telescope:prune --hours=48')->daily();
