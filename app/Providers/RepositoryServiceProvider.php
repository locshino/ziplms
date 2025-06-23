<?php

namespace App\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $interfacePath = app_path('Repositories/Contracts');
        $repositoryPath = app_path('Repositories'); // For context, not strictly needed in the loop

        if (! File::isDirectory($interfacePath)) {
            return; // Exit if the directory doesn't exist
        }

        $interfaces = File::allFiles($interfacePath);

        foreach ($interfaces as $interfaceFile) {
            $interfaceName = $interfaceFile->getFilenameWithoutExtension(); // e.g., UserRepositoryInterface
            $repositoryName = str_replace('Interface', '', $interfaceName); // e.g., UserRepository

            $interfaceFQCN = "App\\Repositories\\Contracts\\{$interfaceName}";
            $repositoryFQCN = "App\\Repositories\\{$repositoryName}";

            // Ensure both the interface and the class exist, and the class implements the interface.
            if (interface_exists($interfaceFQCN) && class_exists($repositoryFQCN) && is_subclass_of($repositoryFQCN, $interfaceFQCN)) {
                $this->app->bind($interfaceFQCN, $repositoryFQCN);
            }
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
