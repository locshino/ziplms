<?php

namespace App\Providers;

use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use BezhanSalleh\FilamentShield\Facades\FilamentShield;
use Illuminate\Support\ServiceProvider;
use pxlrbt\FilamentExcel\FilamentExport;

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
        /** @var \Illuminate\Foundation\Application $app */
        $app = $this->app;

        FilamentShield::prohibitDestructiveCommands($app->isProduction());

        FilamentExport::createExportUrlUsing(function ($export) {
            $fileInfo = pathinfo($export['filename']);
            $filenameWithoutExtension = $fileInfo['filename'];
            $extension = $fileInfo['extension'];

            return \Illuminate\Support\Facades\URL::temporarySignedRoute(
                'filament-excel.exports.download',
                now()->addHours(2),
                ['path' => $filenameWithoutExtension, 'extension' => $extension]
            );
        });

        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->visible(outsidePanels: true)
                ->locales(['vi', 'en']); // also accepts a closure
        });
    }
}
