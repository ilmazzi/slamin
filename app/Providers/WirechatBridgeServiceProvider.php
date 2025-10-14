<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class WirechatBridgeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Registra Wirechat SOLO se non stiamo girando in console (deploy/migrate/config:cache, ecc.)
        if (! $this->app->runningInConsole()) {
            $this->app->register(\Wirechat\Wirechat\WirechatServiceProvider::class);
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Nulla: tutta la logica sta nella register() con il guard runningInConsole()
    }
}
