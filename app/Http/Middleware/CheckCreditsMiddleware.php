<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckCreditsMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
            
            // Check if user has sufficient credits for certain actions
            if ($request->routeIs('matches.create') && !$user->hasCredits(1)) {
                if ($request->expectsJson()) {
                    return response()->json(['error' => 'Insufficient credits'], 402);
                }
                return redirect()->route('credits.purchase')
                    ->with('error', 'You need at least 1 credit to create a match.');
            }
        }

        return $next($request);
    }
}