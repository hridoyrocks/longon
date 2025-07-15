<?php
// bootstrap/app.php - Laravel 11 way

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register middleware aliases
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'reseller' => \App\Http\Middleware\ResellerMiddleware::class,
            'check.credits' => \App\Http\Middleware\CheckCreditsMiddleware::class,
            'track.activity' => \App\Http\Middleware\TrackActivityMiddleware::class,
        ]);

        // Global middleware
        $middleware->append([
            \App\Http\Middleware\TrackActivityMiddleware::class,
        ]);

        // API middleware
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);

        // Web middleware
        $middleware->web(append: [
            \App\Http\Middleware\CheckCreditsMiddleware::class,
        ]);

        // Throttle middleware
        $middleware->throttleApi('60,1'); // 60 requests per minute for API
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();