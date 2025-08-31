<?php

namespace App\Providers;

use App\Providers\Concerns\HasRegisterClass;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
class AppServiceProvider extends ServiceProvider
{
    use HasRegisterClass;

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->registerServices();
        $this->registerRepositories();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        $this->registerObservers();
        // Lấy các thông báo trong bảng 'notifications' mà được tạo trước hơn 1 tuần
        DB::table('notifications')
            ->whereDate('created_at', '<', now()->subWeek()) // chỉ lấy thông báo cũ hơn 1 tuần
            ->chunkById(1000, function ($notifications) {
                // xử lý theo từng chunk 1000 bản ghi để tránh lỗi bộ nhớ
                foreach ($notifications as $notification) {
                    // Xóa từng thông báo dựa trên id
                    DB::table('notifications')->where('id', $notification->id)->delete();
                }
            });
    }

    /**
     * Auto-discover and register services with their interfaces.
     */
    protected function registerServices(): void
    {
        $this->discoverAndRegister(
            path: app_path('Services'),
            namespace: 'App\\Services',
            registrationLogic: function (string $implementationClass) {
                $className = class_basename($implementationClass);
                $interfaceClass = "App\\Services\\Interfaces\\{$className}Interface";

                if (interface_exists($interfaceClass)) {
                    $this->app->bind($interfaceClass, $implementationClass);
                }
            },
            skipClasses: ['BaseService']
        );
    }

    /**
     * Auto-discover and register repositories with their interfaces.
     */
    protected function registerRepositories(): void
    {
        $this->discoverAndRegister(
            path: app_path('Repositories/Eloquent'),
            namespace: 'App\\Repositories\\Eloquent',
            registrationLogic: function (string $implementationClass) {
                $className = class_basename($implementationClass);
                $interfaceClass = "App\\Repositories\\Interfaces\\{$className}Interface";

                if (interface_exists($interfaceClass)) {
                    $this->app->bind($interfaceClass, $implementationClass);
                }
            },
            skipClasses: ['EloquentRepository']
        );
    }

    /**
     * Auto-discover and register Eloquent observers with their models.
     */
    protected function registerObservers(): void
    {
        $this->discoverAndRegister(
            path: app_path('Observers'),
            namespace: 'App\\Observers',
            registrationLogic: function (string $observerClass) {
                $className = class_basename($observerClass);
                $modelName = str_replace('Observer', '', $className);
                $modelClass = "App\\Models\\{$modelName}";

                if (class_exists($modelClass)) {
                    $modelClass::observe($observerClass);
                }
            }
        );
    }

    /**
     * Auto-discover and register model policies.
     */
    protected function registerPolicies(): void
    {
        Gate::guessPolicyNamesUsing(function (string $modelClass) {
            // 'App\Models\User' -> 'App\Policies\UserPolicy'
            return str_replace('Models', 'Policies', $modelClass) . 'Policy';
        });
    }
}
