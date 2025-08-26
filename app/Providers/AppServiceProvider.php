<?php

namespace App\Providers;

use App\Providers\Concerns\HasRegisterClass;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    use HasRegisterClass;
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->autoRegister();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Automatically register services and repositories with their interfaces.
     * This method uses the HasRegisterClass trait to organize the registration logic
     * and binds implementations to their corresponding interfaces in the service container.
     */
    protected function autoRegister(): void
    {
        // Register all services with their interfaces
        $this->registerClassesFromDirectory(
            path: app_path('Services'),
            namespace: 'App\\Services',
            interfaceNamespace: 'App\\Services\\Interfaces',
            skipClasses: ['BaseService']
        );

        // Register all repositories with their interfaces
        $this->registerClassesFromDirectory(
            path: app_path('Repositories/Eloquent'),
            namespace: 'App\\Repositories\\Eloquent',
            interfaceNamespace: 'App\\Repositories\\Interfaces',
            skipClasses: ['EloquentRepository']
        );

        // Register all policies with their models
        Gate::guessPolicyNamesUsing(function (string $modelClass) {
            return str_replace('Models', 'Policies', $modelClass).'Policy';
        });
    }
}
