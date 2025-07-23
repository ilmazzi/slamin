<?php

namespace App\Providers;

use App\Models\Video;
use App\Observers\VideoObserver;
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
        // Registra l'observer per i video
        Video::observe(VideoObserver::class);
    }
}
