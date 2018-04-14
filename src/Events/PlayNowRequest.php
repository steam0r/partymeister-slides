<?php

namespace Partymeister\Slides\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Motor\Media\Models\File;
use Motor\Media\Transformers\FileTransformer;
use Partymeister\Slides\Models\Playlist;
use Partymeister\Slides\Models\Slide;
use Partymeister\Slides\Transformers\PlaylistItemTransformer;
use Partymeister\Slides\Transformers\PlaylistTransformer;
use Partymeister\Slides\Transformers\SlideTransformer;

class PlayNowRequest implements ShouldBroadcast
{

    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $item;


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($type, $item)
    {
        switch ($type) {
            case 'file':
                $file                 = File::find($item);
                $data                 = fractal($file, new FileTransformer())->toArray();
                $data['data']['type'] = 'image';
                $data['data']['slide_type'] = 'default';
                if ($file->getFirstMedia('file')->mime_type == 'video/mp4') {
                    $data['data']['type'] = 'video';
                }
                //dd($data);
                $this->item = $data['data'];
                break;
            case 'slide':
                $file                 = Slide::find($item);
                $data                 = fractal($file, new SlideTransformer())->toArray();
                $data['data']['type'] = 'image';
                $this->item           = $data['data'];
                break;
        }
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('slidemeister');
    }
}
