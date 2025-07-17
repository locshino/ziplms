<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class FilamentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $providers = [
            \Studio15\FilamentTree\FilamentTreeServiceProvider::class,
            \App\Providers\LanguageSwitchProvider::class,
            \App\Providers\LaravelHealthProvider::class,
            \App\Providers\FilamentColorProvider::class,
            \App\Providers\FilamentExcelServiceProvider::class,
        ];

        foreach ($providers as $provider) {
            $this->app->register($provider);
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
