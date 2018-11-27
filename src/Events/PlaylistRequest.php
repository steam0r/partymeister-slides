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
use Motor\Media\Transformers\FileTransformer;
use Partymeister\Slides\Models\Playlist;
use Partymeister\Slides\Transformers\PlaylistItemTransformer;
use Partymeister\Slides\Transformers\PlaylistTransformer;
use Partymeister\Slides\Transformers\SlideTransformer;

class PlaylistRequest implements ShouldBroadcastNow
{

    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $playlist;


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Playlist $playlist, $callbacks)
    {
        $playlistData                         = fractal($playlist, new PlaylistTransformer())->toArray();
        $playlistData['data']['callbacks']    = (bool) $callbacks;
        $playlistData['data']['callback_url'] = env('APP_URL') . '/api/callback/';

        foreach ($playlist->items()->orderBy('sort_position', 'ASC')->get() as $item) {
            $f = null;
            $i = fractal($item, new PlaylistItemTransformer())->toArray();
            if ($item->slide_id != null) {
                $f = fractal($item->slide, new SlideTransformer())->toArray();
            } elseif ($item->file_association != null) {
                $f = fractal($item->file_association->file, new FileTransformer())->toArray();
            }

            if ($f != null) {
                $i['data']       = array_merge($i['data'], $f['data']);
                $i['data']['id'] = $item->id;
                //$i['data']['file'] = $f['data'];
                $playlistItems[] = $i['data'];
            }
        }
        $this->playlist          = $playlistData['data'];
        $this->playlist['items'] = $playlistItems;
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel(config('cache.prefix').':slidemeister-web.' . session('screens.active'));
    }
}
