<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Auth\Events\Login;
use App\Listeners\BroadcastUserLogin;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Gli eventi da ascoltare.
     */
    protected $listen = [
        Login::class => [
            BroadcastUserLogin::class,
        ],
    ];

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
