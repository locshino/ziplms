<?php

namespace App\Providers;

use BezhanSalleh\FilamentLanguageSwitch\Enums\Placement;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use Illuminate\Support\ServiceProvider;

class FilamentServiceProvider extends ServiceProvider
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
                ->visible(outsidePanels: true)
                ->outsidePanelRoutes([
                    'welcome',
                    'auth.login',
                    'auth.profile',
                    'auth.register',
                    // Additional custom routes where the switcher should be visible outside panels
                ])
                ->outsidePanelPlacement(Placement::TopLeft)
                ->locales(['en', 'vi']); // also accepts a closure
        });
    }
}
