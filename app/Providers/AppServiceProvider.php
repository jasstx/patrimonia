<?php

namespace App\Providers;

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
    public function boot()
    {
        // Enregistrement forcé du middleware
        $router = $this->app['router'];
        $router->aliasMiddleware('role', \App\Http\Middleware\CheckRole::class);
    }
}


