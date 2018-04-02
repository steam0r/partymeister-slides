<?php

namespace Partymeister\Slides\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Collection;
use Partymeister\Slides\Models\Playlist;
use Partymeister\Slides\Models\Slide;

class SlideCollectionSaved
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $slideIds;

    public $namePrefix = '';

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Collection $slideIds, $namePrefix)
    {
        $this->slideIds = $slideIds;
        $this->namePrefix = $namePrefix;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
