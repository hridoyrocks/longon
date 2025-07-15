<?php
// app/Events/MatchUpdated.php
namespace App\Events;

use App\Models\Match;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MatchUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $match;

    public function __construct(Match $match)
    {
        $this->match = $match;
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
            'match' => $this->match->toArray()
        ];
    }
}