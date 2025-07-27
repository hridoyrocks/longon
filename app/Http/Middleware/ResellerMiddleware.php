<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResellerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->isApprovedReseller()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Reseller access required'], 403);
            }
            return redirect()->route('reseller.apply')->with('error', 'You need to be an approved reseller to access this page.');
        }

        return $next($request);
    }
}