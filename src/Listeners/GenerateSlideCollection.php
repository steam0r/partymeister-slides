<?php

namespace Partymeister\Slides\Listeners;

use Partymeister\Slides\Events\SlideCollectionSaved;
use Partymeister\Slides\Events\SlideSaved;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class GenerateSlideCollection
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SlideSaved $event
     *
     * @return void
     */
    public function handle(SlideCollectionSaved $event)
    {
        \Partymeister\Slides\Jobs\GenerateSlideCollection::dispatch($event->slideIds, $event->namePrefix);

        //foreach ($event->playlist->playlist_items as $item) {
        //    if ($item->slide_id != null) {
        //    }
        //}
    }
}
