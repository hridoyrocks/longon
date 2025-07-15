<?php
// app/Http/Controllers/DashboardController.php
namespace App\Http\Controllers;

use App\Models\Match;
use App\Models\CreditTransaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $stats = [
            'credits_balance' => $user->credits_balance,
            'total_matches' => $user->matches()->count(),
            'live_matches' => $user->matches()->where('status', 'live')->count(),
            'total_spent' => $user->total_spent,
        ];

        $recentMatches = $user->matches()
            ->latest()
            ->limit(5)
            ->get();

        $recentTransactions = $user->creditTransactions()
            ->latest()
            ->limit(10)
            ->get();

        return view('dashboard', compact('stats', 'recentMatches', 'recentTransactions'));
    }
}