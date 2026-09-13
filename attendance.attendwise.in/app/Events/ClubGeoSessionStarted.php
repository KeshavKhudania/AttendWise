<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ClubGeoSessionStarted implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $clubId;
    public $uuid;
    public $clubName;
    public $venue;
    public $startedBy;

    public function __construct($clubId, $uuid, $clubName, $venue, $startedBy)
    {
        $this->clubId = $clubId;
        $this->uuid = $uuid;
        $this->clubName = $clubName;
        $this->venue = $venue;
        $this->startedBy = $startedBy;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('club.' . $this->clubId),
        ];
    }
    
    public function broadcastAs(): string
    {
        return 'ClubGeoSessionStarted';
    }
}
