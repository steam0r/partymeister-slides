<?php

namespace Partymeister\Slides\Http\Controllers\Api\SlideClients;

use Illuminate\Http\Request;
use Motor\Backend\Http\Controllers\Controller;
use Partymeister\Slides\Services\XMLService;

class CommunicationController extends Controller
{

    public function playlist(Request $request)
    {
        $result = XMLService::send('playlist', $request->all());
        if ( ! $result) {
            return response()->json([ 'result' => $result ], 400);
        } else {
            return response()->json([ 'result' => $result ]);
        }
    }


    public function playnow(Request $request)
    {
        $result = XMLService::send('playnow', $request->all());
        if ( ! $result) {
            return response()->json([ 'result' => $result ], 400);
        } else {
            return response()->json([ 'result' => $result ]);
        }
    }

    public function seek(Request $request)
    {
        $result = XMLService::send('seek', $request->all());
        if ( ! $result) {
            return response()->json([ 'result' => $result ], 400);
        } else {
            return response()->json([ 'result' => $result ]);
        }
    }

    public function skip(Request $request)
    {
        $result = XMLService::send($request->get('direction'), ['hard' => $request->get('hard')]);
        if ( ! $result) {
            return response()->json([ 'result' => $result ], 400);
        } else {
            return response()->json([ 'result' => $result ]);
        }
    }

    public function get_system_info(Request $request)
    {
        $result = XMLService::send('get_system_info');
        if ( ! $result) {
            return response()->json([ 'result' => $result ], 400);
        } else {
            return response()->json([ 'result' => $result ]);
        }
    }

    public function get_playlists(Request $request)
    {
        $result = XMLService::send('get_playlists');
        if ( ! $result) {
            return response()->json([ 'result' => $result ], 400);
        } else {
            return response()->json([ 'result' => $result ]);
        }
    }

}