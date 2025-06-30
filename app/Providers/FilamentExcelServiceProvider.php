<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use pxlrbt\FilamentExcel\FilamentExport;

class FilamentExcelServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // No services need to be registered for this provider.
    }

    /**
     * Bootstrap any application services.
     *
     * This method is called after all other service providers have been registered,
     * meaning we can safely access them here.
     */
    public function boot(): void
    {
        $this->configureCustomExportUrl();
    }

    /**
     * Configures a custom, WAF-friendly URL generation logic for file exports.
     *
     * By default, the package generates a signed URL with the full filename in the path.
     * This can trigger false positives on some Web Application Firewalls (WAF).
     * This method overrides the default behavior to create a cleaner URL structure.
     */
    private function configureCustomExportUrl(): void
    {
        FilamentExport::createExportUrlUsing(function (array $export) {
            return \App\Support\FileDownloadHelper::generateWafFriendlySignedUrl(filePath: $export['filename'], isErrorReport: false);
        });
    }
}
