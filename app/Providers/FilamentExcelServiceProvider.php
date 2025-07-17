<?php

namespace App\Providers;

use App\Jobs\ProcessExportJob;
use App\Jobs\ProcessImportJob;
use Filament\Actions\Exports\Jobs\PrepareCsvExport as BaseExportJob;
use Filament\Actions\Imports\Jobs\ImportCsv as BaseImportJob;
use Illuminate\Support\ServiceProvider;

// use pxlrbt\FilamentExcel\FilamentExport;

class FilamentExcelServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(BaseImportJob::class, ProcessImportJob::class);
        $this->app->bind(BaseExportJob::class, ProcessExportJob::class);
    }

    /**
     * Bootstrap any application services.
     *
     * This method is called after all other service providers have been registered,
     * meaning we can safely access them here.
     */
    public function boot(): void
    {
        //
    }
}
