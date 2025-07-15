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
        'status', 'match_time', 'is_premium', 'overlay_settings',
        'started_at', 'finished_at'
    ];

    protected $casts = [
        'overlay_settings' => 'array',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
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
}