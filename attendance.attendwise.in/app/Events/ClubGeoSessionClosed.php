<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ClubGeoSessionClosed implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $clubId;
    public $uuid;

    public function __construct($clubId, $uuid)
    {
        $this->clubId = $clubId;
        $this->uuid = $uuid;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('club.' . $this->clubId),
        ];
    }
    
    public function broadcastAs(): string
    {
        return 'ClubGeoSessionClosed';
    }
}
