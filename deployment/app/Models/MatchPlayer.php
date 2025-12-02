<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class MatchPlayer extends Pivot
{
    protected $table = 'match_players';

    protected $fillable = [
        'match_id',
        'player_id',
        'team',
        'is_starting_11',
        'is_substitute'
    ];

    protected $casts = [
        'is_starting_11' => 'boolean',
        'is_substitute' => 'boolean',
    ];
}
