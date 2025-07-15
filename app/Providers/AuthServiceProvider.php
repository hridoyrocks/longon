<?php
namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Match;
use App\Models\User;
use App\Policies\MatchPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Match::class => MatchPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        // Additional gates
        Gate::define('view-admin-panel', function (User $user) {
            return $user->user_type === 'admin';
        });

        Gate::define('manage-resellers', function (User $user) {
            return $user->user_type === 'admin';
        });

        Gate::define('access-analytics', function (User $user) {
            return $user->user_type === 'admin' || $user->isApprovedReseller();
        });
    }
}