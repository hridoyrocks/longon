<?php
// app/Events/MatchEventAdded.php
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
        $this->event = $event;
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
        return [
            'event' => $this->event->toArray()
        ];
    }
}