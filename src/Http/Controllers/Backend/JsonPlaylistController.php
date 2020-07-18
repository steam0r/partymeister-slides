<?php

namespace Partymeister\Slides\Http\Controllers\Backend;

use Illuminate\Http\Response;
use Motor\Backend\Http\Controllers\Controller;
use Partymeister\Slides\Events\PlaylistRequest;
use Partymeister\Slides\Models\Playlist;

/**
 * Class SlideClientsController
 * @package Partymeister\Slides\Http\Controllers\Backend
 */
class JsonPlaylistController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function show(Playlist $playlist)
    {
        $request = new PlaylistRequest($playlist, true);
        return response()->json($request);
    }


}
