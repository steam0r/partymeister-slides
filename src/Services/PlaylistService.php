<?php

namespace Partymeister\Slides\Services;

use Motor\Media\Models\FileAssociation;
use Partymeister\Slides\Models\Playlist;
use Motor\Backend\Services\BaseService;
use Partymeister\Slides\Models\PlaylistItem;
use Partymeister\Slides\Models\Transition;

class PlaylistService extends BaseService
{

    protected $model = Playlist::class;


    public function afterCreate()
    {
        $this->savePlaylistItems();
    }


    public function afterUpdate()
    {
        $this->savePlaylistItems();
    }


    protected function savePlaylistItems()
    {
        $items = json_decode($this->request->get('playlist_items'));

        // Delete all playlist items for this playlist
        foreach ($this->record->items()->get() as $item) {
            $item->file_association()->delete();
            $item->delete();
        }

        // Create new playlist items
        foreach ($items as $item) {
            $i              = new PlaylistItem();
            $i->playlist_id = $this->record->id;
            $i->type        = $this->getType($item);

            $transition = Transition::where('identifier', $item->transition_identifier)->first();

            $i->duration             = $item->duration;
            $i->transition_id        = ( is_null($transition) ? null : $transition->id );
            $i->transition_duration  = $item->transition_duration;
            $i->is_advanced_manually = $item->is_advanced_manually;
            $i->midi_note            = $item->midi_note;
            $i->callback_hash        = $item->callback_hash;
            $i->callback_delay       = $item->callback_delay;

            if (property_exists($item, 'slide_type')) {
                $i->slide_id = $item->id;
                $i->slide_type = $item->slide_type;
            }

            // Fixme: implement this
            $i->is_muted = false;
            $i->save();

            if ( ! property_exists($item, 'slide_type')) {
                // Create file association
                $fa             = new FileAssociation();
                $fa->file_id    = $item->id;
                $fa->model_type = get_class($i);
                $fa->model_id   = $i->id;
                $fa->identifier = 'playlist_item';
                $fa->save();
            }

        }

    }


    protected function getType($item)
    {
        if (in_array($item->file->mime_type, [ 'image/png', 'image/jpg' ])) {
            return 'image';
        }

        return '';
    }
}
