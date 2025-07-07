<?php

namespace App\Providers;

use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Support\ServiceProvider;

class FilamentColorProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        FilamentColor::register([
            // Main brand color
            'primary' => Color::Indigo,

            // Secondary color for information
            'info' => Color::Violet,

            // Standard colors
            'success' => Color::Green,
            'warning' => Color::Amber,
            'danger' => Color::Red,

            // Neutral UI color
            'gray' => Color::Zinc,
        ]);
    }
}
