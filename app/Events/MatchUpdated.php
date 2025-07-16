<?php
// app/Events/MatchUpdated.php - Fixed Version

namespace App\Events;

use App\Models\FootballMatch; // Use FootballMatch instead of Match
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MatchUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $match;
    public $updateType;

    public function __construct(FootballMatch $match, $updateType = 'general')
    {
        $this->match = $match;
        $this->updateType = $updateType;
    }

    public function broadcastOn()
    {
        return new Channel('match.' . $this->match->id);
    }

    public function broadcastAs()
    {
        return 'match-updated';
    }

    public function broadcastWith()
    {
        return [
            'match' => [
                'id' => $this->match->id,
                'teamA' => $this->match->team_a,
                'teamB' => $this->match->team_b,
                'teamAScore' => $this->match->team_a_score,
                'teamBScore' => $this->match->team_b_score,
                'matchTimeMinutes' => floor($this->match->match_time),
                'matchTimeSeconds' => ($this->match->match_time * 60) % 60,
                'status' => $this->match->status,
                'last_updated' => $this->match->updated_at->timestamp,
            ],
            'updateType' => $this->updateType,
            'timestamp' => now()->timestamp
        ];
    }
}