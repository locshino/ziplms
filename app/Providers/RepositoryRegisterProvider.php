<?php

namespace App\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class RepositoryRegisterProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->autoRegisterRepositories();
    }

    /**
     * Automatically scan and register repositories and their interfaces.
     */
    protected function autoRegisterRepositories()
    {
        // Path to the Eloquent repositories
        $repositoryPath = app_path('Repositories/Eloquent');

        if (! File::isDirectory($repositoryPath)) {
            return;
        }

        // Get all PHP files in the directory
        $repositoryFiles = File::files($repositoryPath);

        foreach ($repositoryFiles as $file) {
            $className = $file->getFilenameWithoutExtension();

            // Skip BaseRepository
            if ($className === 'EloquentRepository') {
                continue;
            }

            // Construct the full class and interface names
            $repositoryClass = "App\\Repositories\\Eloquent\\{$className}";
            $interfaceClass = "App\\Repositories\\Interfaces\\{$className}Interface";

            // Check if the class and interface exist before binding
            if (class_exists($repositoryClass) && interface_exists($interfaceClass)) {
                $this->app->bind($interfaceClass, $repositoryClass);
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
