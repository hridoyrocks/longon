<?php
// app/Http/Controllers/DashboardController.php
namespace App\Http\Controllers;

use App\Models\FootballMatch;
use App\Models\MatchEvent;
use App\Models\CreditTransaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Stats for dashboard
        $totalMatches = $user->matches()->count();
        $liveMatches = $user->matches()->where('status', 'live')->count();
        $totalEvents = MatchEvent::whereHas('footballMatch', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->count();
        
        // Recent matches
        $recentMatches = $user->matches()
            ->with('events')
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'totalMatches',
            'liveMatches',
            'totalEvents',
            'recentMatches'
        ));
    }
}