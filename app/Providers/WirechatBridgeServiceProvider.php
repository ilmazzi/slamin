<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class WirechatBridgeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Configura morph maps PRIMA di registrare Wirechat
        $this->configureMorphMaps();
        
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
    
    /**
     * Configura i morph maps per tutti i modelli
     */
    private function configureMorphMaps(): void
    {
        Relation::enforceMorphMap([
            'user' => \App\Models\User::class,
            'video' => \App\Models\Video::class,
            'poem' => \App\Models\Poem::class,
            'article' => \App\Models\Article::class,
            'photo' => \App\Models\Photo::class,
            'gig' => \App\Models\Gig::class,
            'event' => \App\Models\Event::class,
            'group' => \App\Models\Group::class,
        ]);
    }
}
