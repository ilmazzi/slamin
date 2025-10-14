<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class WirechatServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Configura il morph map PRIMA che Wirechat venga caricato
        Relation::enforceMorphMap([
            'user' => \App\Models\User::class,
        ]);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Assicurati che il morph map sia configurato
        Relation::enforceMorphMap([
            'user' => \App\Models\User::class,
        ]);
    }
}
