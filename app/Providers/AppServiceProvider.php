<?php

namespace App\Providers;

use App\Repositories\Contracts\ContactRepositoryInterface;
use App\Repositories\EloquentContactRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind repository interface to implementation
        $this->app->bind(
            ContactRepositoryInterface::class,
            EloquentContactRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
