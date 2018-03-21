<?php

Route::group([
    'as'         => 'backend.',
    'prefix'     => 'backend',
    'namespace'  => 'Partymeister\Slides\Http\Controllers\Backend',
    'middleware' => [
        'web',
        'web_auth',
        'navigation'
    ]
], function () {

    Route::group([ 'middleware' => [ 'permission' ] ], function () {
        Route::resource('slides', 'SlidesController')->except(['create', 'show']);
        Route::get('slides/{slide}/duplicate',
            'SlidesController@duplicate')->name('slides.duplicate');
        Route::get('slides/create/{slide_template}', 'SlidesController@create')->name('slides.create');

        Route::resource('slide_templates', 'SlideTemplatesController')->except('show');
        Route::get('slide_templates/{slide_template}/duplicate',
            'SlideTemplatesController@duplicate')->name('slide_templates.duplicate');

        Route::resource('playlists', 'PlaylistsController');
        Route::resource('transitions', 'TransitionsController');
    });
});

Route::get('backend/slide_templates/{slide_template}', 'Partymeister\Slides\Http\Controllers\Backend\SlideTemplatesController@show')->middleware(['bindings', 'navigation'])->name('backend.slide_templates.show');
Route::get('backend/slides/{slide}', 'Partymeister\Slides\Http\Controllers\Backend\SlidesController@show')->middleware(['bindings', 'navigation'])->name('backend.slides.show');
