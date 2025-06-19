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
            // 1. Deconstruct the filename to separate the name and extension.
            // This avoids putting a full file path in the URL parameters.
            $fileInfo = pathinfo($export['filename']);
            $filenameWithoutExtension = $fileInfo['filename'];
            $extension = $fileInfo['extension'];

            // 2. Generate a temporary signed URL pointing to our custom download route.
            // This URL is more secure and less likely to be blocked by a WAF.
            return URL::temporarySignedRoute(
                'exports.download', // The name of our custom download route.
                now()->addHours(2), // Set a custom expiration time for the link.
                [
                    // Pass the file parts as separate parameters.
                    'filename' => $filenameWithoutExtension,
                    'extension' => $extension,
                ]
            );
        });
    }
}
