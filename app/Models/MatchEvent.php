<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'match_id', 'event_type', 'team', 'player', 'minute', 'description'
    ];

    public function footballMatch()
    {
        return $this->belongsTo(\App\Models\FootballMatch::class, 'match_id');
    }

    public function getEventIcon()
    {
        $icons = [
            'goal' => '⚽',
            'yellow_card' => '🟨',
            'red_card' => '🟥',
            'substitution' => '🔄',
            'penalty' => '🥅',
        ];

        return $icons[$this->event_type] ?? '⚽';
    }
}