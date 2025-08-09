<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Repositories\Eloquent\CourseRepository;
use App\Repositories\Interfaces\EnrollmentRepositoryInterface;
use App\Repositories\Eloquent\EnrollmentRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            abstract: CourseRepositoryInterface::class,
            concrete: CourseRepository::class
        );

        $this->app->bind(
            abstract: EnrollmentRepositoryInterface::class,
            concrete: EnrollmentRepository::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}