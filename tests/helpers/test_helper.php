<?php

function create_test_slide($count = 1)
{
    return factory(\Partymeister\Slides\\Models\Slide::class, $count)->create();
}

function create_test_slide_template($count = 1)
{
    return factory(Partymeister\Slides\Models\SlideTemplate::class, $count)->create();
}

function create_test_playlist($count = 1)
{
    return factory(Partymeister\Slides\Models\Playlist::class, $count)->create();
}

function create_test_transition($count = 1)
{
    return factory(Partymeister\Slides\Models\Transition::class, $count)->create();
}
