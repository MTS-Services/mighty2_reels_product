<?php

namespace App\Providers;

use App\Services\Auth\OtpRateLimitingService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;

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
        Model::preventLazyLoading();
        Model::automaticallyEagerLoadRelationships();
    }
}
