<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\MatchingService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register the MatchingService
        $this->app->singleton(MatchingService::class, function ($app) {
            return new MatchingService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}