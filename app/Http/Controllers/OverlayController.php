<?php
// app/Http/Controllers/OverlayController.php - Updated with Smart Timer
namespace App\Http\Controllers;

use App\Models\OverlayToken;
use App\Models\MatchAnalytics;
use Illuminate\Http\Request;

class OverlayController extends Controller
{
    public function show($token)
    {
        $overlayToken = OverlayToken::where('token', $token)
            ->with('match.events')
            ->first();

        if (!$overlayToken || $overlayToken->isExpired()) {
            abort(404, 'Overlay not found or expired');
        }

        $match = $overlayToken->match;
        $showWatermark = !$match->is_premium;

        // Get recent events with proper formatting
        $events = $match->events()
            ->latest()
            ->limit(3)
            ->get()
            ->map(function($event) use ($match) {
                return [
                    'minute' => $event->minute,
                    'type' => $event->event_type,
                    'player' => $event->player ?: 'Unknown',
                    'team' => $event->team === 'team_a' ? $match->team_a : $match->team_b,
                    'icon' => $this->getEventIcon($event->event_type)
                ];
            });

        return view('overlay.show', compact('match', 'showWatermark', 'overlayToken', 'events'));
    }

    public function getOverlayData($matchId)
    {
        $match = \App\Models\FootballMatch::with('events')->find($matchId);
        
        if (!$match) {
            return response()->json(['success' => false, 'message' => 'Match not found'], 404);
        }

        // Track analytics
        $this->trackView($match);

        // Get recent events
        $events = $match->events()
            ->latest()
            ->limit(3)
            ->get()
            ->map(function($event) use ($match) {
                return [
                    'minute' => $event->minute,
                    'type' => $event->event_type,
                    'player' => $event->player ?: 'Unknown',
                    'team' => $event->team === 'team_a' ? $match->team_a : $match->team_b,
                    'icon' => $this->getEventIcon($event->event_type),
                    'timestamp' => $event->created_at->timestamp
                ];
            });

        // Calculate smart timer
        $smartTime = $this->calculateSmartTime($match);

        return response()->json([
            'success' => true,
            'match' => [
                'id' => $match->id,
                'team_a' => $match->team_a,
                'team_b' => $match->team_b,
                'team_a_score' => $match->team_a_score,
                'team_b_score' => $match->team_b_score,
                'match_time' => $smartTime,
                'status' => $match->status,
                'started_at' => $match->started_at,
                'is_premium' => $match->is_premium,
                'last_updated' => $match->updated_at->timestamp
            ],
            'events' => $events,
            'analytics' => [
                'total_views' => $match->analytics->overlay_views ?? 0,
                'unique_views' => $match->analytics->overlay_unique_views ?? 0
            ]
        ]);
    }

    private function calculateSmartTime($match)
    {
        if ($match->status !== 'live' || !$match->started_at) {
            return $match->match_time;
        }

        // Calculate elapsed time since match started
        $startTime = $match->started_at;
        $currentTime = now();
        $elapsedMinutes = $currentTime->diffInMinutes($startTime);

        // Use the higher value between manual time and calculated time
        return max($match->match_time, $elapsedMinutes);
    }

    private function getEventIcon($eventType)
    {
        $icons = [
            'goal' => '⚽',
            'yellow_card' => '🟨',
            'red_card' => '🟥',
            'substitution' => '🔄',
            'penalty' => '🥅',
            'corner' => '🚩',
            'foul' => '⚠️',
            'offside' => '🚫'
        ];

        return $icons[$eventType] ?? '⚽';
    }

    private function trackView($match)
    {
        // Get or create analytics
        $analytics = $match->analytics;
        
        if (!$analytics) {
            $analytics = MatchAnalytics::create([
                'match_id' => $match->id,
                'total_goals' => $match->events()->where('event_type', 'goal')->count(),
                'total_cards' => $match->events()->whereIn('event_type', ['yellow_card', 'red_card'])->count(),
                'total_substitutions' => $match->events()->where('event_type', 'substitution')->count(),
                'overlay_views' => 0,
                'overlay_unique_views' => 0,
                'engagement_score' => 0,
            ]);
        }

        // Track view
        $sessionKey = 'viewed_match_' . $match->id;
        $isUnique = !session()->has($sessionKey);
        
        $analytics->increment('overlay_views');
        
        if ($isUnique) {
            $analytics->increment('overlay_unique_views');
            session()->put($sessionKey, true);
        }

        // Update last viewed
        $analytics->touch();
    }

    public function trackViewApi(Request $request, $token)
    {
        $overlayToken = OverlayToken::where('token', $token)->first();

        if (!$overlayToken) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid token'
            ], 404);
        }

        $this->trackView($overlayToken->match);

        return response()->json([
            'success' => true,
            'message' => 'View tracked successfully'
        ]);
    }

    public function heartbeat(Request $request, $token)
    {
        $overlayToken = OverlayToken::where('token', $token)->first();

        if (!$overlayToken) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid token'
            ], 404);
        }

        $match = $overlayToken->match;

        // Simple heartbeat response
        return response()->json([
            'success' => true,
            'status' => $match->status,
            'last_updated' => $match->updated_at->timestamp,
            'server_time' => now()->timestamp
        ]);
    }

    public function getTheme($token)
    {
        $overlayToken = OverlayToken::where('token', $token)->first();

        if (!$overlayToken) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid token'
            ], 404);
        }

        $match = $overlayToken->match;
        $theme = $match->overlay_settings ?? [];

        return response()->json([
            'success' => true,
            'theme' => $theme
        ]);
    }
}