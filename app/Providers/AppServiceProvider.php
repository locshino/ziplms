<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /**
         * Listen for the 'created' event on the User model.
         * This is a reliable way to track successful imports when using ToModel,
         * as it fires *after* the model has been successfully saved to the database.
         */
        User::created(function (User $user) {
            // Check if the user was created during an import process
            if (property_exists($user, 'importBatch') && $user->importBatch) {
                $user->importBatch->increment('successful_imports');
                $user->importBatch->increment('processed_rows');
            }
        });
    }
}
