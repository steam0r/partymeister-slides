<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

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
    'middleware' => [ 'bindings' ],
    'namespace'  => 'Partymeister\Slides\Http\Controllers\Api',
    'prefix'     => 'api',
    'as'         => 'api.',
], function () {
    Route::resource('slide_clients', 'SlideClientsController');
});

// FIXME: put this in a controller so we can use route caching
Route::post('ajax/slidemeister-web/{slide_client}/status', function (Request $request, $slide_client) {
    Cache::store('redis')->put(config('cache.prefix').':slidemeister-web.'.$slide_client, $request->all(), 3600);
})->name('ajax.slidemeister-web.status.update');

Route::group([
    'middleware' => [ 'web', 'web_auth', 'bindings', 'permission' ],
    'namespace'  => 'Partymeister\Slides\Http\Controllers\Api',
    'prefix'     => 'ajax',
    'as'         => 'ajax.',
], function () {
    Route::get('transitions', 'TransitionsController@index')->name('transitions.index');
    Route::get('playlists', 'PlaylistsController@show')->name('playlists.index');
    Route::get('playlists/items/{playlist_item}', 'Playlists\ItemsController@show')->name('playlists.items.show');
    Route::post('slide_templates', 'SlideTemplatesController@preview')->name('slide_templates.preview');
    Route::get('slides', 'SlidesController@index')->name('slides.index');
    Route::post('slide_clients/communication/playlist', 'SlideClients\CommunicationController@playlist')->name('slide_clients.communication.playlist');
    Route::post('slide_clients/communication/playnow', 'SlideClients\CommunicationController@playnow')->name('slide_clients.communication.playnow');
    Route::post('slide_clients/communication/seek', 'SlideClients\CommunicationController@seek')->name('slide_clients.communication.seek');
    Route::post('slide_clients/communication/siegmeister', 'SlideClients\CommunicationController@siegmeister')->name('slide_clients.communication.siegmeister');
    Route::post('slide_clients/communication/skip', 'SlideClients\CommunicationController@skip')->name('slide_clients.communication.skip');
    Route::get('slide_clients/communication/system', 'SlideClients\CommunicationController@get_system_info')->name('slide_clients.communication.system');
    Route::get('slide_clients/communication/playlists', 'SlideClients\CommunicationController@get_playlists')->name('slide_clients.communication.playlists');
});

Route::group([
    'middleware' => [ 'bindings' ],
    //'namespace'  => '',
    'prefix'     => 'ajax',
    'as'         => 'ajax.frontend.',
], function () {
    Route::post('slide_clients/communication/skip-for-revision', 'SlideClients\CommunicationController@skip')->name('slide_clients.communication.skipforrevision');
    Route::get('frontend-playlists/{playlist}', 'Partymeister\Slides\Http\Controllers\Api\PlaylistsController@show')->name('playlists.show');
});
