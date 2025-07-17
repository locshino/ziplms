<?php

namespace App\Providers;

use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use Illuminate\Support\ServiceProvider;

class LanguageSwitchProvider extends ServiceProvider
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
        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->locales(['vi', 'en'])
                ->visible(outsidePanels: true)
                ->labels([
                    'en' => 'English',
                    'vi' => 'Tiếng Việt',
                ])
                ->outsidePanelRoutes([
                    'login',
                    'home',
                ]);
        });
    }
}
