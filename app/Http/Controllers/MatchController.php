<?php
namespace App\Http\Controllers;

use App\Models\FootballMatch;
use App\Models\MatchEvent;
use App\Models\CreditTransaction;
use App\Models\OverlayToken;
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

        $match->update([
            'team_a_score' => $request->team_a_score,
            'team_b_score' => $request->team_b_score,
        ]);

        return response()->json(['success' => true]);
    }

    public function updateTime(Request $request, FootballMatch $match)
    {
        // Check if user owns this match
        if ($match->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'match_time' => 'required|integer|min:0',
        ]);

        $match->update([
            'match_time' => $request->match_time,
        ]);

        return response()->json(['success' => true]);
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

        $match->update([
            'status' => $request->status,
            'started_at' => $request->status === 'live' ? now() : $match->started_at,
            'finished_at' => $request->status === 'finished' ? now() : null,
        ]);

        return response()->json(['success' => true]);
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

        return response()->json(['success' => true, 'event' => $event]);
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

        return response()->json(['overlay_url' => $overlayUrl]);
    }
}