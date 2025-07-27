<?php
// app/Http/Controllers/AnalyticsController.php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Match;
use App\Models\PaymentRequest;
use App\Models\MatchAnalytics;
use App\Models\CreditTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        
        // User's personal analytics
        $userStats = [
            'total_matches' => $user->matches()->count(),
            'total_spent' => $user->total_spent,
            'avg_match_duration' => $user->matches()->avg('match_time'),
            'most_used_team' => $this->getMostUsedTeam($user->id),
            'credits_used_this_month' => $this->getCreditsUsedThisMonth($user->id),
            'match_frequency' => $this->getMatchFrequency($user->id),
        ];

        // Match analytics
        $matchStats = $user->matches()
            ->with('analytics')
            ->latest()
            ->limit(10)
            ->get()
            ->map(function ($match) {
                return [
                    'id' => $match->id,
                    'teams' => $match->team_a . ' vs ' . $match->team_b,
                    'date' => $match->created_at->format('M d, Y'),
                    'views' => $match->analytics->overlay_views ?? 0,
                    'engagement' => $match->analytics->engagement_score ?? 0,
                    'duration' => $match->match_time,
                ];
            });

        // Monthly revenue (if reseller)
        $monthlyRevenue = [];
        if ($user->isApprovedReseller()) {
            $monthlyRevenue = $this->getMonthlyRevenue($user->id);
        }

        return view('analytics.dashboard', compact('userStats', 'matchStats', 'monthlyRevenue'));
    }

    public function matchDetails(Match $match)
    {
        $this->authorize('view', $match);
        
        $analytics = $match->analytics;
        
        if (!$analytics) {
            // Create analytics if not exists
            $analytics = MatchAnalytics::create([
                'match_id' => $match->id,
                'total_goals' => $match->events()->where('event_type', 'goal')->count(),
                'total_cards' => $match->events()->whereIn('event_type', ['yellow_card', 'red_card'])->count(),
                'total_substitutions' => $match->events()->where('event_type', 'substitution')->count(),
                'overlay_views' => 0,
                'engagement_score' => 0,
            ]);
        }

        $eventTimeline = $match->events()
            ->orderBy('minute')
            ->get()
            ->map(function ($event) {
                return [
                    'minute' => $event->minute,
                    'type' => $event->event_type,
                    'team' => $event->team,
                    'player' => $event->player,
                    'icon' => $event->getEventIcon(),
                ];
            });

        return view('analytics.match-details', compact('match', 'analytics', 'eventTimeline'));
    }

    public function businessDashboard()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $stats = [
            'total_revenue' => PaymentRequest::where('status', 'approved')->sum('amount'),
            'monthly_revenue' => PaymentRequest::where('status', 'approved')
                ->whereMonth('approved_at', now()->month)
                ->sum('amount'),
            'total_users' => User::count(),
            'active_users' => User::where('last_activity', '>=', now()->subDays(30))->count(),
            'total_matches' => Match::count(),
            'total_overlay_views' => MatchAnalytics::sum('overlay_views'),
        ];

        $revenueChart = $this->getRevenueChart();
        $userGrowthChart = $this->getUserGrowthChart();
        $topPerformers = $this->getTopPerformers();

        return view('analytics.business-dashboard', compact('stats', 'revenueChart', 'userGrowthChart', 'topPerformers'));
    }

    private function getMostUsedTeam($userId)
    {
        $teamA = Match::where('user_id', $userId)
            ->select('team_a as team')
            ->groupBy('team_a')
            ->selectRaw('COUNT(*) as count');

        $teamB = Match::where('user_id', $userId)
            ->select('team_b as team')
            ->groupBy('team_b')
            ->selectRaw('COUNT(*) as count');

        $result = $teamA->union($teamB)
            ->orderBy('count', 'desc')
            ->first();

        return $result ? $result->team : 'N/A';
    }

    private function getCreditsUsedThisMonth($userId)
    {
        return CreditTransaction::where('user_id', $userId)
            ->where('transaction_type', 'debit')
            ->whereMonth('created_at', now()->month)
            ->sum('credits_used');
    }

    private function getMatchFrequency($userId)
    {
        $matches = Match::where('user_id', $userId)
            ->where('created_at', '>=', now()->subDays(30))
            ->count();

        return round($matches / 30, 1);
    }

    private function getMonthlyRevenue($resellerId)
    {
        return PaymentRequest::whereHas('user', function ($query) use ($resellerId) {
                $query->where('referrer_id', $resellerId);
            })
            ->where('status', 'approved')
            ->whereYear('approved_at', now()->year)
            ->select(
                DB::raw('MONTH(approved_at) as month'),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->mapWithKeys(function ($item) {
                return [now()->month($item->month)->format('M') => $item->total];
            });
    }

    private function getRevenueChart()
    {
        return PaymentRequest::where('status', 'approved')
            ->whereYear('approved_at', now()->year)
            ->select(
                DB::raw('MONTH(approved_at) as month'),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->mapWithKeys(function ($item) {
                return [now()->month($item->month)->format('M') => $item->total];
            });
    }

    private function getUserGrowthChart()
    {
        return User::whereYear('created_at', now()->year)
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->mapWithKeys(function ($item) {
                return [now()->month($item->month)->format('M') => $item->total];
            });
    }

    private function getTopPerformers()
    {
        return User::where('user_type', 'reseller')
            ->whereNotNull('reseller_approved_at')
            ->orderBy('total_commission_earned', 'desc')
            ->limit(10)
            ->get(['name', 'total_commission_earned', 'total_referrals']);
    }
}