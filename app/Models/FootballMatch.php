<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FootballMatch extends Model
{
    use HasFactory;

    protected $table = 'matches'; // Keep original table name

    protected $fillable = [
        'user_id', 'team_a', 'team_b', 'team_a_score', 'team_b_score',
        'status', 'match_time', 'is_timer_running', 'is_premium', 'overlay_settings',
        'started_at', 'finished_at', 'is_tie_breaker', 'tie_breaker_data',
        'penalty_shootout_enabled', 'tournament_name', 'show_player_list'
    ];

    protected $casts = [
        'overlay_settings' => 'array',
        'tie_breaker_data' => 'array',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
        'is_tie_breaker' => 'boolean',
        'is_timer_running' => 'boolean',
        'penalty_shootout_enabled' => 'boolean',
        'show_player_list' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function events()
    {
        return $this->hasMany(\App\Models\MatchEvent::class, 'match_id');
    }

    public function overlayTokens()
    {
        return $this->hasMany(\App\Models\OverlayToken::class, 'match_id');
    }

    public function isLive()
    {
        return $this->status === 'live';
    }

    public function isFinished()
    {
        return $this->status === 'finished';
    }

    public function getWinner()
    {
        if ($this->team_a_score > $this->team_b_score) {
            return $this->team_a;
        } elseif ($this->team_b_score > $this->team_a_score) {
            return $this->team_b;
        }
        return 'Draw';
    }

    public function players()
    {
        return $this->belongsToMany(\App\Models\Player::class, 'match_players', 'match_id', 'player_id')
                    ->withPivot('team', 'is_starting_11', 'is_substitute')
                    ->withTimestamps();
    }

    public function homePlayers()
    {
        return $this->players()->wherePivot('team', 'home');
    }

    public function awayPlayers()
    {
        return $this->players()->wherePivot('team', 'away');
    }
}