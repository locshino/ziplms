<?php

namespace App\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class ServiceRegisterProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->autoRegisterServices();
    }

    /**
     * Automatically scan and register services and their interfaces.
     */
    protected function autoRegisterServices()
    {
        $servicePath = app_path('Services');

        if (! File::isDirectory($servicePath)) {
            return;
        }

        $serviceFiles = File::files($servicePath);

        foreach ($serviceFiles as $file) {
            $className = $file->getFilenameWithoutExtension();

            // Skip BaseService
            if ($className === 'BaseService') {
                continue;
            }

            $serviceClass = "App\\Services\\{$className}";
            $interfaceClass = "App\\Services\\Interfaces\\{$className}Interface";

            if (class_exists($serviceClass) && interface_exists($interfaceClass)) {
                $this->app->bind($interfaceClass, $serviceClass);
            }
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
