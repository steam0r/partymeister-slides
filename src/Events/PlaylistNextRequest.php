<?php

namespace Partymeister\Slides\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Partymeister\Slides\Models\Playlist;

class PlaylistNextRequest implements ShouldBroadcastNow
{

    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $hard = false;


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($hard = false)
    {
        $this->hard   = $hard;
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel(config('cache.prefix').':slidemeister-web.'.session('screens.active'));
    }
}
