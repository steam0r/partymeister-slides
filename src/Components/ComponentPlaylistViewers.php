<?php

namespace Partymeister\Slides\Components;

use Illuminate\Http\Request;
use Motor\CMS\Models\PageVersionComponent;
use Motor\Media\Transformers\FileTransformer;
use Partymeister\Slides\Models\Playlist;
use Partymeister\Slides\Transformers\PlaylistItemTransformer;
use Partymeister\Slides\Transformers\PlaylistTransformer;
use Partymeister\Slides\Transformers\SlideTransformer;

class ComponentPlaylistViewers {

    protected $component;
    protected $pageVersionComponent;
    protected $playlist;

    public function __construct(PageVersionComponent $pageVersionComponent, \Partymeister\Slides\Models\Component\ComponentPlaylistViewer $component)
    {
        $this->component = $component;
        $this->pageVersionComponent = $pageVersionComponent;
    }

    public function index(Request $request)
    {
        $playlist = Playlist::find($this->component->playlist_id);
        // Get slides
        $playlistItems                        = [];
        $playlistData                         = fractal($playlist, new PlaylistTransformer())->toArray();

        foreach ($playlist->items()->orderBy('sort_position', 'ASC')->get() as $item) {
            $f = null;
            $i = fractal($item, new PlaylistItemTransformer())->toArray();
            if ($item->slide_id != null) {
                $f = fractal($item->slide, new SlideTransformer())->toArray();
            } elseif ($item->file_association != null) {
                $f = fractal($item->file_association->file, new FileTransformer())->toArray();
            }

            if ($f != null) {
                $i['data']       = array_merge($i['data'], $f['data']);
                $i['data']['id'] = $item->id;
                //$i['data']['file'] = $f['data'];
                $playlistItems[] = $i['data'];
            }
        }
        $this->playlist          = $playlistData['data'];
        $this->playlist['items'] = $playlistItems;

        return $this->render();
    }


    public function render()
    {
        return view(config('motor-cms-page-components.components.'.$this->pageVersionComponent->component_name.'.view'), ['component' => $this->component, 'playlist' => $this->playlist]);
    }

}
