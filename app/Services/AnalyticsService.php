<?php
// app/Services/AnalyticsService.php
namespace App\Services;

use App\Models\Match;
use App\Models\MatchAnalytics;
use App\Models\User;
use App\Models\PaymentRequest;
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    public function updateMatchAnalytics(Match $match)
    {
        $analytics = $match->analytics ?: new MatchAnalytics(['match_id' => $match->id]);

        $analytics->total_goals = $match->events()->where('event_type', 'goal')->count();
        $analytics->total_cards = $match->events()->whereIn('event_type', ['yellow_card', 'red_card'])->count();
        $analytics->total_substitutions = $match->events()->where('event_type', 'substitution')->count();

        // Calculate engagement score
        $engagementScore = $this->calculateEngagementScore($match);
        $analytics->engagement_score = $engagementScore;

        // Generate event timeline
        $eventTimeline = $match->events()
            ->orderBy('minute')
            ->get()
            ->map(function ($event) {
                return [
                    'minute' => $event->minute,
                    'type' => $event->event_type,
                    'team' => $event->team,
                    'player' => $event->player,
                ];
            });

        $analytics->event_timeline = $eventTimeline;
        $analytics->save();

        return $analytics;
    }

    private function calculateEngagementScore(Match $match)
    {
        $score = 0;

        // Base score from events
        $score += $match->events()->where('event_type', 'goal')->count() * 10;
        $score += $match->events()->where('event_type', 'yellow_card')->count() * 3;
        $score += $match->events()->where('event_type', 'red_card')->count() * 7;
        $score += $match->events()->where('event_type', 'substitution')->count() * 2;

        // Match duration factor
        if ($match->match_time > 0) {
            $score += min($match->match_time / 10, 10);
        }

        // Overlay views factor
        if ($match->analytics) {
            $score += min($match->analytics->overlay_views / 10, 20);
        }

        return min($score, 100);
    }

    public function getBusinessAnalytics()
    {
        return [
            'total_revenue' => PaymentRequest::where('status', 'approved')->sum('amount'),
            'monthly_revenue' => PaymentRequest::where('status', 'approved')
                ->whereMonth('approved_at', now()->month)
                ->sum('amount'),
            'total_users' => User::count(),
            'active_users' => User::where('last_activity', '>=', now()->subDays(30))->count(),
            'total_resellers' => User::where('user_type', 'reseller')->count(),
            'total_matches' => Match::count(),
            'total_overlay_views' => MatchAnalytics::sum('overlay_views'),
            'average_engagement' => MatchAnalytics::avg('engagement_score'),
        ];
    }

    public function getRevenueChart($period = 'monthly')
    {
        $query = PaymentRequest::where('status', 'approved');

        if ($period === 'monthly') {
            return $query->whereYear('approved_at', now()->year)
                ->select(
                    DB::raw('MONTH(approved_at) as period'),
                    DB::raw('SUM(amount) as total')
                )
                ->groupBy('period')
                ->orderBy('period')
                ->get()
                ->mapWithKeys(function ($item) {
                    return [now()->month($item->period)->format('M') => $item->total];
                });
        }

        return $query->whereYear('approved_at', now()->year)
            ->select(
                DB::raw('DATE(approved_at) as period'),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('period')
            ->orderBy('period')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->period => $item->total];
            });
    }
}