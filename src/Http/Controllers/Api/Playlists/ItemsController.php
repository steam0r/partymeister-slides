<?php

namespace Partymeister\Slides\Http\Controllers\Api\Playlists;

use Motor\Backend\Http\Controllers\Controller;

use Motor\Media\Transformers\FileTransformer;
use Partymeister\Slides\Models\Playlist;
use Partymeister\Slides\Http\Requests\Backend\PlaylistRequest;
use Partymeister\Slides\Models\PlaylistItem;
use Partymeister\Slides\Services\PlaylistService;
use Partymeister\Slides\Transformers\PlaylistItemTransformer;
use Partymeister\Slides\Transformers\PlaylistTransformer;
use Partymeister\Slides\Transformers\SlideTransformer;

class ItemsController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show(PlaylistItem $record)
    {
        $i = fractal($record, new PlaylistItemTransformer())->toArray();
        if ($record->slide_id != null) {
            $f = fractal($record->slide, new SlideTransformer())->toArray();
        } elseif ($record->file_association != null) {
            $f = fractal($record->file_association->file, new FileTransformer())->toArray();
        }

        if ($f != null) {
            $i['data'] = array_merge($i['data'], $f['data']);
            return response()->json(['data' => $i['data']]);
        }
        return response()->json(['error' => 'Not found'], 404);

    }
}