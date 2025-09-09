<?php

namespace App\Providers;

use App\Services\Auth\OtpRateLimitingService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(OtpRateLimitingService::class, function ($app) {
            return new OtpRateLimitingService();
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
