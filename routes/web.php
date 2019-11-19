<?php

use Illuminate\Http\Request;
use Partymeister\Slides\Models\Playlist;
use Partymeister\Slides\Models\SlideClient;

// FIXME: put this in a controller so we can use the Route caching
Route::get('slidemeister-web/{slide_client}', function (SlideClient $slideClient) {
    return view('partymeister-slides::slidemeister-web/index', ['slideClient' => $slideClient, 'channelPrefix' => config('cache.prefix')]);
})->middleware(['bindings'])->name('backend.slidemeister-web.show');

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
        Route::get(
            'slides/{slide}/duplicate',
            'SlidesController@duplicate'
        )->name('slides.duplicate');
        Route::get('slides/create/{slide_template}', 'SlidesController@create')->name('slides.create');

        Route::resource('slide_templates', 'SlideTemplatesController')->except('show');
        Route::get(
            'slide_templates/{slide_template}/duplicate',
            'SlideTemplatesController@duplicate'
        )->name('slide_templates.duplicate');

        Route::resource('playlists', 'PlaylistsController');
        Route::resource('transitions', 'TransitionsController');
        Route::resource('slide_clients', 'SlideClientsController');
        Route::get('slide_clients/{slide_client}/activate', 'SlideClientsController@activate')->name('slide_clients.activate');

        Route::resource('files', 'FilesController');
    });
});

Route::get('backend/slide_templates/{slide_template}.html', 'Partymeister\Slides\Http\Controllers\Backend\SlideTemplatesController@show')->middleware(['bindings'])->name('backend.slide_templates.show');
Route::get('backend/slides/{slide}.html', 'Partymeister\Slides\Http\Controllers\Backend\SlidesController@show')->middleware(['bindings', 'cache.headers:etag'])->name('backend.slides.show');

// FIXME: put these in controllers so we can use the Route caching
//Route::get('test-prizegiving', function() {
//    $xml = \Partymeister\Slides\Services\XMLService::send('playlist', array('playlist_id' => 148));
//    return response($xml, 200)
//        ->header('Content-Type', 'text/xml');    //echo $xml;
//});
//
//Route::get('xmlservice/playlist', function() {
//    //$result = XMLMeister::send('playlist', array('playlist_id' => arr::get($_GET, 'play'), 'callbacks' => arr::get($_GET, 'callbacks')));
//    $xml = \Partymeister\Slides\Services\XMLService::send('playlist', ['playlist_id' => \Partymeister\Slides\Models\Playlist::find(194)->id, 'callbacks' => 0], false, true);
//    return response($xml, 200)
//        ->header('Content-Type', 'text/xml');    //echo $xml;
//});
//
//Route::get('xmlservice/next', function() {
//    $xml = \Partymeister\Slides\Services\XMLService::send('next', ['hard' => true], false, true);
//    return response($xml, 200)
//        ->header('Content-Type', 'text/xml');    //echo $xml;
//});
//
//Route::get('xmlservice/previous', function() {
//    $xml = \Partymeister\Slides\Services\XMLService::send('previous', ['hard' => true], false, true);
//    return response($xml, 200)
//        ->header('Content-Type', 'text/xml');    //echo $xml;
//});
//
//Route::get('xmlservice/getplaylists', function() {
//    $xml = \Partymeister\Slides\Services\XMLService::send('get_playlists', ['hard' => true], false, true);
//    return response($xml, 200)
//        ->header('Content-Type', 'text/xml');    //echo $xml;
//});
