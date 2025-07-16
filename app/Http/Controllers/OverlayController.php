<?php
// app/Http/Controllers/OverlayController.php - Fixed Version

namespace App\Http\Controllers;

use App\Models\OverlayToken;
use App\Models\MatchAnalytics;
use App\Models\FootballMatch;
use Illuminate\Http\Request;
use Carbon\Carbon;

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

        // Get recent events
        $events = $match->events()
            ->latest()
            ->limit(5)
            ->get()
            ->map(function($event) use ($match) {
                return [
                    'minute' => $event->minute,
                    'type' => $event->event_type,
                    'player' => $event->player ?: 'Unknown',
                    'team' => $event->team === 'team_a' ? $match->team_a : $match->team_b,
                    'timestamp' => $event->created_at->timestamp
                ];
            });

        return view('overlay.show', compact('match', 'showWatermark', 'overlayToken', 'events'));
    }

    public function getOverlayData($matchId)
    {
        $match = FootballMatch::with(['events' => function($query) {
            $query->latest()->limit(10);
        }, 'analytics'])->find($matchId);
        
        if (!$match) {
            return response()->json(['success' => false, 'message' => 'Match not found'], 404);
        }

        // Track analytics
        $this->trackView($match);

        // Get recent events
        $events = $match->events->map(function($event) use ($match) {
            return [
                'minute' => $event->minute,
                'type' => $event->event_type,
                'player' => $event->player ?: 'Unknown',
                'team' => $event->team === 'team_a' ? $match->team_a : $match->team_b,
                'timestamp' => $event->created_at->timestamp
            ];
        });

        // Calculate smart timer
        $timerData = $this->calculateSmartTime($match);

        return response()->json([
            'success' => true,
            'match' => [
                'id' => $match->id,
                'teamA' => $match->team_a,
                'teamB' => $match->team_b,
                'teamAScore' => $match->team_a_score,
                'teamBScore' => $match->team_b_score,
                'matchTimeMinutes' => $timerData['minutes'],
                'matchTimeSeconds' => $timerData['seconds'],
                'status' => $match->status,
                'started_at' => $match->started_at,
                'is_premium' => $match->is_premium,
                'last_updated' => $match->updated_at->timestamp,
                'events' => $events
            ],
            'server_time' => now()->timestamp
        ]);
    }

    private function calculateSmartTime($match)
    {
        $baseMinutes = (int) $match->match_time;
        $baseSeconds = 0;

        if ($match->status === 'live' && $match->started_at) {
            // Calculate elapsed time since match started
            $startTime = Carbon::parse($match->started_at);
            $currentTime = now();
            $elapsedSeconds = $currentTime->diffInSeconds($startTime);
            
            $calculatedMinutes = intval($elapsedSeconds / 60);
            $calculatedSeconds = $elapsedSeconds % 60;

            // Use the higher value between manual time and calculated time
            if ($calculatedMinutes > $baseMinutes || 
                ($calculatedMinutes == $baseMinutes && $calculatedSeconds > $baseSeconds)) {
                $baseMinutes = $calculatedMinutes;
                $baseSeconds = $calculatedSeconds;
            }
        }

        return [
            'minutes' => $baseMinutes,
            'seconds' => $baseSeconds
        ];
    }

    private function trackView($match)
    {
        // Get or create analytics
        $analytics = $match->analytics;
        
        if (!$analytics) {
            $analytics = MatchAnalytics::create([
                'match_id' => $match->id,
                'total_goals' => $match->team_a_score + $match->team_b_score,
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
}