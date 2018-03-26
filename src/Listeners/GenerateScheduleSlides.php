<?php

namespace Partymeister\Slides\Listeners;

use Partymeister\Core\Events\EventSaved;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class GenerateScheduleSlides
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
     * @param  EventSaved  $event
     * @return void
     */
    public function handle(EventSaved $event)
    {
        //
    }
}
