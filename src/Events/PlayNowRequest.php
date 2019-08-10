<?php

namespace Partymeister\Slides\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Motor\Media\Models\File;
use Motor\Media\Transformers\FileTransformer;
use Partymeister\Slides\Models\Slide;
use Partymeister\Slides\Transformers\SlideTransformer;

/**
 * Class PlayNowRequest
 * @package Partymeister\Slides\Events
 */
class PlayNowRequest implements ShouldBroadcastNow
{

    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var
     */
    public $item;


    /**
     * Create a new event instance.
     *
     * PlayNowRequest constructor.
     * @param $type
     * @param $item
     */
    public function __construct($type, $item)
    {
        switch ($type) {
            case 'file':
                $file                       = File::find($item);
                $data                       = fractal($file, new FileTransformer())->toArray();
                $data['data']['type']       = 'image';
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
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new Channel(config('cache.prefix') . ':slidemeister-web.' . session('screens.active'));
    }
}
