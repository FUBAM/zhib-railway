<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\\Models\\Model' => 'App\\Policies\\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Simple gate: 'admin' returns true when user->role === 'admin'
        Gate::define('admin', function (?User $user) {
            return $user && $user->role === 'admin';
        });

        // Optional: 'moderator' gate (in case we need it later)
        Gate::define('moderator', function (?User $user) {
            return $user && in_array($user->role, ['admin', 'moderator']);
        });
    }
}
