<?php
Route::group([
    'middleware' => [ 'auth:api', 'bindings', 'permission' ],
    'namespace'  => 'Partymeister\Slides\Http\Controllers\Api',
    'prefix'     => 'api',
    'as'         => 'api.',
], function () {
    Route::resource('slides', 'SlidesController');
    Route::resource('slide_templates', 'SlideTemplatesController');
    Route::resource('playlists', 'PlaylistsController');
    Route::resource('transitions', 'TransitionsController');
});


Route::group([
    'middleware' => [ 'web', 'web_auth', 'bindings', 'permission' ],
    'namespace'  => 'Partymeister\Slides\Http\Controllers\Api',
    'prefix'     => 'ajax',
    'as'         => 'ajax.',
], function () {
    Route::get('transitions', 'TransitionsController@index')->name('transitions.index');
    Route::post('slide_templates', 'SlideTemplatesController@preview')->name('slide_templates.preview');
    Route::get('slides', 'SlidesController@index')->name('slides.index');
});
