<?php

$factory->define(\Partymeister\Slides\\Models\Slide::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word
    ];
});

$factory->define(Partymeister\Slides\Models\SlideTemplate::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word
    ];
});

$factory->define(Partymeister\Slides\Models\Playlist::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word
    ];
});

$factory->define(Partymeister\Slides\Models\Transition::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word
    ];
});
