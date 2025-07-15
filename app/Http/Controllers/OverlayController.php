<?php
namespace App\Http\Controllers;

use App\Models\OverlayToken;
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

        return view('overlay.show', compact('match', 'showWatermark', 'overlayToken'));
    }

    public function getOverlayData($matchId)
    {
        $match = \App\Models\FootballMatch::with('events')->find($matchId);
        
        if (!$match) {
            return response()->json(['success' => false, 'message' => 'Match not found'], 404);
        }

        $events = $match->events()->latest()->limit(3)->get()->map(function ($event) {
            return [
                'minute' => $event->minute,
                'event_type' => $event->event_type,
                'team' => $event->team,
                'player' => $event->player,
            ];
        });

        return response()->json([
            'success' => true,
            'match' => [
                'id' => $match->id,
                'team_a' => $match->team_a,
                'team_b' => $match->team_b,
                'team_a_score' => $match->team_a_score,
                'team_b_score' => $match->team_b_score,
                'match_time' => $match->match_time,
                'status' => $match->status,
            ],
            'events' => $events
        ]);
    }
}