<?php
// app/Http/Controllers/MatchController.php - Fixed with Broadcasting

namespace App\Http\Controllers;

use App\Models\FootballMatch;
use App\Models\MatchEvent;
use App\Models\CreditTransaction;
use App\Models\OverlayToken;
use App\Events\MatchUpdated;
use App\Events\MatchEventAdded;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MatchController extends Controller
{
    public function index()
    {
        $matches = auth()->user()->matches()->latest()->paginate(10);
        return view('matches.index', compact('matches'));
    }

    public function create()
    {
        $user = auth()->user();
        
        if (!$user->hasCredits(1)) {
            return redirect()->route('credits.purchase')
                ->with('error', 'আপনার পর্যাপ্ত ক্রেডিট নেই। অনুগ্রহ করে ক্রেডিট কিনুন।');
        }

        return view('matches.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'team_a' => 'required|string|max:255',
            'team_b' => 'required|string|max:255',
            'tournament_name' => 'nullable|string|max:255',
        ]);

        $user = auth()->user();

        if (!$user->hasCredits(1)) {
            return redirect()->route('credits.purchase')
                ->with('error', 'আপনার পর্যাপ্ত ক্রেডিট নেই।');
        }

        $balanceBefore = $user->credits_balance;
        $user->deductCredits(1);

        $match = FootballMatch::create([
            'user_id' => $user->id,
            'team_a' => $request->team_a,
            'team_b' => $request->team_b,
            'tournament_name' => $request->tournament_name,
            'is_premium' => $user->credits_balance > 0,
        ]);

        // Record credit transaction
        CreditTransaction::create([
            'user_id' => $user->id,
            'match_id' => $match->id,
            'credits_used' => 1,
            'transaction_type' => 'debit',
            'balance_before' => $balanceBefore,
            'balance_after' => $user->credits_balance,
            'description' => 'Match created: ' . $match->team_a . ' vs ' . $match->team_b,
        ]);

        return redirect()->route('matches.control', $match->id)
            ->with('success', 'ম্যাচ সফলভাবে তৈরি হয়েছে!');
    }

    public function control(FootballMatch $match)
    {
        // Check if user owns this match
        if ($match->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }
        
        $events = $match->events()->latest()->get();
        $overlayToken = $match->overlayTokens()->latest()->first();

        return view('matches.control', compact('match', 'events', 'overlayToken'));
    }

    public function updateScore(Request $request, FootballMatch $match)
    {
        // Check if user owns this match
        if ($match->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'team_a_score' => 'required|integer|min:0',
            'team_b_score' => 'required|integer|min:0',
        ]);

        $oldScoreA = $match->team_a_score;
        $oldScoreB = $match->team_b_score;

        $match->update([
            'team_a_score' => $request->team_a_score,
            'team_b_score' => $request->team_b_score,
        ]);

        // Determine update type for enhanced animations
        $updateType = 'score';
        if ($request->team_a_score > $oldScoreA) {
            $updateType = 'goal_team_a';
        } elseif ($request->team_b_score > $oldScoreB) {
            $updateType = 'goal_team_b';
        }

        // Fire the event to broadcast
        event(new MatchUpdated($match, $updateType));

        return response()->json([
            'success' => true,
            'update_type' => $updateType,
            'message' => 'Score updated and broadcasted'
        ]);
    }

    public function updateTime(Request $request, FootballMatch $match)
    {
        // Check if user owns this match
        if ($match->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'match_time' => 'required|numeric|min:0',
            'is_timer_running' => 'nullable|boolean',
        ]);

        $updateData = [
            'match_time' => $request->match_time,
        ];
        
        // Save timer running state if provided
        if ($request->has('is_timer_running')) {
            $updateData['is_timer_running'] = $request->is_timer_running;
        }

        $match->update($updateData);

        // Fire the event to broadcast
        event(new MatchUpdated($match, 'time'));

        return response()->json([
            'success' => true,
            'message' => 'Time updated and broadcasted',
            'is_timer_running' => $match->is_timer_running
        ]);
    }

    public function updateStatus(Request $request, FootballMatch $match)
    {
        // Check if user owns this match
        if ($match->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'status' => 'required|in:pending,live,finished',
        ]);

        $oldStatus = $match->status;

        $updateData = [
            'status' => $request->status,
        ];

        // Set timestamps based on status
        if ($request->status === 'live' && $oldStatus !== 'live') {
            $updateData['started_at'] = now();
        } elseif ($request->status === 'finished') {
            $updateData['finished_at'] = now();
        }

        $match->update($updateData);

        // Fire the event to broadcast
        event(new MatchUpdated($match, 'status'));

        return response()->json([
            'success' => true,
            'message' => 'Status updated and broadcasted'
        ]);
    }

    public function addEvent(Request $request, FootballMatch $match)
    {
        // Check if user owns this match
        if ($match->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'event_type' => 'required|in:goal,yellow_card,red_card,substitution,penalty',
            'team' => 'required|in:team_a,team_b',
            'player' => 'nullable|string|max:255',
            'minute' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $event = MatchEvent::create([
            'match_id' => $match->id,
            'event_type' => $request->event_type,
            'team' => $request->team,
            'player' => $request->player,
            'minute' => $request->minute,
            'description' => $request->description,
        ]);

        // Fire the event to broadcast
        event(new MatchEventAdded($event));

        return response()->json([
            'success' => true,
            'event' => $event,
            'message' => 'Event added and broadcasted'
        ]);
    }

    public function generateOverlayLink(FootballMatch $match)
    {
        // Check if user owns this match
        if ($match->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $token = Str::random(32);
        
        OverlayToken::create([
            'match_id' => $match->id,
            'token' => $token,
            'expires_at' => now()->addDays(7),
        ]);

        $overlayUrl = route('overlay.show', $token);

        return response()->json([
            'overlay_url' => $overlayUrl,
            'success' => true
        ]);
    }
    
    public function updateTieBreaker(Request $request, FootballMatch $match)
    {
        // Check if user owns this match
        if ($match->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'is_tie_breaker' => 'required|boolean',
            'tie_breaker_data' => 'nullable|array',
        ]);

        $match->update([
            'is_tie_breaker' => $request->is_tie_breaker,
            'tie_breaker_data' => $request->tie_breaker_data,
        ]);

        // Fire the event to broadcast
        event(new MatchUpdated($match, 'tie_breaker'));

        return response()->json([
            'success' => true,
            'message' => 'Tie-breaker data updated and broadcasted'
        ]);
    }
    
    public function updateSettings(Request $request, FootballMatch $match)
    {
        // Check if user owns this match
        if ($match->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'penalty_shootout_enabled' => 'nullable|boolean',
        ]);

        $updateData = [];
        
        if ($request->has('penalty_shootout_enabled')) {
            $updateData['penalty_shootout_enabled'] = $request->penalty_shootout_enabled;
        }

        $match->update($updateData);
        
        // Broadcast the update to overlay
        event(new MatchUpdated($match, 'settings'));

        return response()->json([
            'success' => true,
            'message' => 'Settings updated successfully'
        ]);
    }
    
    public function updatePenalty(Request $request, FootballMatch $match)
    {
        // Check if user owns this match
        if ($match->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'tie_breaker_data' => 'required|array',
            'tie_breaker_data.team_a' => 'required|array',
            'tie_breaker_data.team_b' => 'required|array',
            'tie_breaker_data.team_a.goals' => 'required|integer|min:0',
            'tie_breaker_data.team_b.goals' => 'required|integer|min:0',
        ]);

        // Update tie breaker data
        $match->update([
            'tie_breaker_data' => $request->tie_breaker_data
        ]);
        
        // Reload to ensure we have the latest data
        $match->refresh();
        
        // Broadcast the update to overlay with penalty update type
        event(new MatchUpdated($match, 'penalty'));

        return response()->json([
            'success' => true,
            'message' => 'Penalty data updated and broadcasted',
            'tie_breaker_data' => $match->tie_breaker_data
        ]);
    }
    
    public function updateTimer(Request $request, FootballMatch $match)
    {
        // Check if user owns this match
        if ($match->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'minutes' => 'required|integer|min:0',
            'seconds' => 'required|integer|min:0|max:59',
            'isRunning' => 'required|boolean',
            'action' => 'required|string',
        ]);

        $match->update([
            'match_time' => $request->minutes + ($request->seconds / 60),
            'is_timer_running' => $request->isRunning
        ]);
        
        // Broadcast timer update
        broadcast(new \App\Events\TimerUpdated($match, [
            'minutes' => $request->minutes,
            'seconds' => $request->seconds,
            'isRunning' => $request->isRunning,
            'action' => $request->action,
            'source' => 'control-' . $match->id
        ]))->toOthers();
        
        return response()->json(['success' => true]);
    }

    public function getTimerState(FootballMatch $match)
    {
        // Check if user owns this match
        if ($match->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        return response()->json([
            'success' => true,
            'minutes' => floor($match->match_time),
            'seconds' => round(($match->match_time * 60) % 60),
            'isRunning' => $match->is_timer_running
        ]);
    }

    public function getTimerStateApi(FootballMatch $match)
    {
        // Public API for overlay
        return response()->json([
            'success' => true,
            'minutes' => floor($match->match_time),
            'seconds' => round(($match->match_time * 60) % 60),
            'isRunning' => $match->is_timer_running
        ]);
    }
}