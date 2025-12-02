<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Player extends Model
{
    protected $fillable = [
        'name',
        'jersey_number',
        'position',
        'photo_url'
    ];

    /**
     * The matches that belong to the player.
     */
    public function matches(): BelongsToMany
    {
        return $this->belongsToMany(FootballMatch::class, 'match_players', 'player_id', 'match_id')
                    ->withPivot('team', 'is_starting_11', 'is_substitute')
                    ->withTimestamps();
    }
}
