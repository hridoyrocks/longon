<?php
namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/dashboard';

    public function boot(): void
    {
        $this->configureRateLimiting();
        
        // Route model bindings
        Route::model('match', \App\Models\Match::class);
        Route::model('user', \App\Models\User::class);
        Route::model('payment', \App\Models\PaymentRequest::class);
        Route::model('theme', \App\Models\OverlayTheme::class);
        Route::model('application', \App\Models\ResellerApplication::class);
        Route::model('commission', \App\Models\CommissionPayment::class);
        Route::model('package', \App\Models\CreditPackage::class);
    }

    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('heavy-api', function (Request $request) {
            return Limit::perMinute(10)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('auth', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });
    }
}