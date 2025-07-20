<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Event;
use App\Policies\EventPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Event::class => EventPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Additional gates for Poetry Slam system
        Gate::define('manage-events', function ($user) {
            return $user->hasPermissionTo('manage events');
        });

        Gate::define('create-events', function ($user) {
            return $user->hasPermissionTo('create events');
        });

        Gate::define('view-analytics', function ($user) {
            return $user->hasPermissionTo('view analytics');
        });

        Gate::define('export-analytics', function ($user) {
            return $user->hasPermissionTo('export analytics');
        });
    }
}
