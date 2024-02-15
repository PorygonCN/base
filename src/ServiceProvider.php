<?php

namespace Porygon\Base;

use Illuminate\Support\ServiceProvider as SupportServiceProvider;
use Porygon\Base\Middleware\LogRequest;

class ServiceProvider extends SupportServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'porygon-base');
        $this->app->singleton(LogRequest::class);
        $this->app->router->aliasMiddleware("log", LogRequest::class);
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/config.php' => config_path('porygon-base.php'),
            __DIR__ . '/../database/migrations' => database_path('migrations/porygon-base'),
        ], "porygon-base");
    }
}
