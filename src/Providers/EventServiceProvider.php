<?php

namespace Partymeister\Slides\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'Partymeister\Slides\Events\SlideSaved' => [
            'Partymeister\Slides\Listeners\GenerateSlide',
        ],
        'Partymeister\Core\Events\EventSaved'   => [
            'Partymeister\Slides\Listeners\GenerateScheduleSlides',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
