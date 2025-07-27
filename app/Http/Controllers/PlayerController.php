<?php

namespace App\Http\Controllers;

use App\Models\FootballMatch;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlayerController extends Controller
{
    /**
     * Get all players for a match
     */
    public function index(FootballMatch $match)
    {
        $match->load(['homePlayers', 'awayPlayers']);
        
        return response()->json([
            'home_players' => $match->homePlayers,
            'away_players' => $match->awayPlayers
        ]);
    }

    /**
     * Add a player to a match
     */
    public function store(Request $request, FootballMatch $match)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'jersey_number' => 'nullable|string|max:10',
            'position' => 'nullable|string|max:50',
            'team' => 'required|in:home,away',
            'is_starting_11' => 'boolean',
            'is_substitute' => 'boolean'
        ]);

        DB::transaction(function () use ($request, $match) {
            $player = Player::create($request->only(['name', 'jersey_number', 'position']));
            
            $match->players()->attach($player->id, [
                'team' => $request->team,
                'is_starting_11' => $request->is_starting_11 ?? true,
                'is_substitute' => $request->is_substitute ?? false
            ]);
        });

        return response()->json(['success' => true]);
    }

    /**
     * Update a player
     */
    public function update(Request $request, FootballMatch $match, Player $player)
    {
        $request->validate([
            'name' => 'string|max:255',
            'jersey_number' => 'nullable|string|max:10',
            'position' => 'nullable|string|max:50',
            'team' => 'in:home,away',
            'is_starting_11' => 'boolean',
            'is_substitute' => 'boolean'
        ]);

        $player->update($request->only(['name', 'jersey_number', 'position']));

        if ($request->has(['team', 'is_starting_11', 'is_substitute'])) {
            $match->players()->updateExistingPivot($player->id, 
                $request->only(['team', 'is_starting_11', 'is_substitute'])
            );
        }

        return response()->json(['success' => true]);
    }

    /**
     * Remove a player from match
     */
    public function destroy(FootballMatch $match, Player $player)
    {
        $match->players()->detach($player->id);
        
        // Delete player if not in any other match
        if ($player->matches()->count() === 0) {
            $player->delete();
        }

        return response()->json(['success' => true]);
    }

    /**
     * Toggle player list visibility
     */
    public function togglePlayerList(FootballMatch $match)
    {
        $match->update(['show_player_list' => !$match->show_player_list]);

        // Broadcast the change
        broadcast(new \App\Events\MatchUpdated($match, 'player_list'));

        return response()->json([
            'success' => true,
            'show_player_list' => $match->show_player_list
        ]);
    }
}
