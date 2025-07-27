<?php
namespace App\Events;

use App\Models\MatchEvent;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MatchEventAdded implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $event;

    public function __construct(MatchEvent $event)
    {
        $this->event = $event->load('footballMatch');
    }

    public function broadcastOn()
    {
        return new Channel('match.' . $this->event->match_id);
    }

    public function broadcastAs()
    {
        return 'event-added';
    }

    public function broadcastWith()
    {
        $match = $this->event->footballMatch;
        
        return [
            'event' => [
                'id' => $this->event->id,
                'type' => $this->event->event_type,
                'team' => $this->event->team,
                'player' => $this->event->player,
                'minute' => $this->event->minute,
                'description' => $this->event->description,
                'team_name' => $this->event->team === 'team_a' ? $match->team_a : $match->team_b,
            ],
            'match_id' => $this->event->match_id,
            'timestamp' => now()->timestamp
        ];
    }
}