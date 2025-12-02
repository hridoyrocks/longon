<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Define Gates for authorization
        Gate::define('admin-access', function (User $user) {
            return $user->isAdmin();
        });

        Gate::define('reseller-access', function (User $user) {
            return $user->isApprovedReseller();
        });

        Gate::define('create-match', function (User $user) {
            return $user->hasCredits(1);
        });

        // View composers for common data
        view()->composer('*', function ($view) {
            if (auth()->check()) {
                $view->with('currentUser', auth()->user());
                $view->with('userCredits', auth()->user()->credits_balance);
            }
        });
    }
}