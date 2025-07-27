<?php
// app/Http/Controllers/Api/MatchApiController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Match;
use App\Models\MatchEvent;
use App\Models\CreditTransaction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MatchApiController extends Controller
{
    public function index(): JsonResponse
    {
        $matches = auth()->user()->matches()
            ->with('events', 'analytics')
            ->latest()
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $matches
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'team_a' => 'required|string|max:255',
            'team_b' => 'required|string|max:255',
        ]);

        $user = auth()->user();

        if (!$user->hasCredits(1)) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient credits'
            ], 400);
        }

        $balanceBefore = $user->credits_balance;
        $user->deductCredits(1);

        $match = Match::create([
            'user_id' => $user->id,
            'team_a' => $request->team_a,
            'team_b' => $request->team_b,
            'is_premium' => $user->credits_balance > 0,
        ]);

        // Record transaction
        CreditTransaction::create([
            'user_id' => $user->id,
            'match_id' => $match->id,
            'credits_used' => 1,
            'transaction_type' => 'debit',
            'balance_before' => $balanceBefore,
            'balance_after' => $user->credits_balance,
            'description' => 'Match created via API',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Match created successfully',
            'data' => $match
        ], 201);
    }

    public function show(Match $match): JsonResponse
    {
        $this->authorize('view', $match);

        $match->load('events', 'analytics');

        return response()->json([
            'success' => true,
            'data' => $match
        ]);
    }

    public function update(Request $request, Match $match): JsonResponse
    {
        $this->authorize('update', $match);

        $request->validate([
            'team_a' => 'sometimes|string|max:255',
            'team_b' => 'sometimes|string|max:255',
            'team_a_score' => 'sometimes|integer|min:0',
            'team_b_score' => 'sometimes|integer|min:0',
            'status' => 'sometimes|in:pending,live,finished',
            'match_time' => 'sometimes|integer|min:0',
        ]);

        $match->update($request->only([
            'team_a', 'team_b', 'team_a_score', 'team_b_score', 'status', 'match_time'
        ]));

        broadcast(new \App\Events\MatchUpdated($match));

        return response()->json([
            'success' => true,
            'message' => 'Match updated successfully',
            'data' => $match
        ]);
    }

    public function destroy(Match $match): JsonResponse
    {
        $this->authorize('delete', $match);

        $match->delete();

        return response()->json([
            'success' => true,
            'message' => 'Match deleted successfully'
        ]);
    }

    public function updateScore(Request $request, Match $match): JsonResponse
    {
        $this->authorize('update', $match);

        $request->validate([
            'team_a_score' => 'required|integer|min:0',
            'team_b_score' => 'required|integer|min:0',
        ]);

        $match->update([
            'team_a_score' => $request->team_a_score,
            'team_b_score' => $request->team_b_score,
        ]);

        broadcast(new \App\Events\MatchUpdated($match));

        return response()->json([
            'success' => true,
            'message' => 'Score updated successfully',
            'data' => $match
        ]);
    }

    public function updateStatus(Request $request, Match $match): JsonResponse
    {
        $this->authorize('update', $match);

        $request->validate([
            'status' => 'required|in:pending,live,finished',
        ]);

        $match->update([
            'status' => $request->status,
            'started_at' => $request->status === 'live' ? now() : $match->started_at,
            'finished_at' => $request->status === 'finished' ? now() : null,
        ]);

        broadcast(new \App\Events\MatchUpdated($match));

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'data' => $match
        ]);
    }

    public function addEvent(Request $request, Match $match): JsonResponse
    {
        $this->authorize('update', $match);

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

        broadcast(new \App\Events\MatchEventAdded($event));

        return response()->json([
            'success' => true,
            'message' => 'Event added successfully',
            'data' => $event
        ]);
    }
}