<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\UserActivityLog;

class TrackActivityMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Track user activity after response
        if (auth()->check() && !$request->routeIs('ajax.*')) {
            UserActivityLog::create([
                'user_id' => auth()->id(),
                'activity_type' => 'page_visit',
                'description' => 'Visited: ' . $request->path(),
                'metadata' => [
                    'url' => $request->fullUrl(),
                    'method' => $request->method(),
                    'user_agent' => $request->userAgent(),
                    'ip_address' => $request->ip(),
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        return $response;
    }
}