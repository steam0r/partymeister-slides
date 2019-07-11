<?php

namespace Partymeister\Slides\Services;

use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;
use Illuminate\Support\Arr;
use Motor\Backend\Models\Category;
use Motor\Core\Filter\Renderers\SelectRenderer;
use Motor\Media\Models\File;
use Motor\Media\Models\FileAssociation;
use Partymeister\Competitions\Helpers\CallbackHelper;
use Partymeister\Competitions\Models\Entry;
use Partymeister\Slides\Events\SlideCollectionSaved;
use Partymeister\Slides\Events\SlideSaved;
use Partymeister\Slides\Models\Playlist;
use Motor\Backend\Services\BaseService;
use Partymeister\Slides\Models\PlaylistItem;
use Partymeister\Slides\Models\Slide;
use Partymeister\Slides\Models\Transition;

class PlaylistService extends BaseService
{

    protected $model = Playlist::class;


    public function filters()
    {
        $this->filter->add(new SelectRenderer('type'))
                     ->setOptionPrefix(trans('partymeister-slides::backend/playlists.type'))
                     ->setEmptyOption('-- ' . trans('partymeister-slides::backend/playlists.type') . ' --')
                     ->setOptions(trans('partymeister-slides::backend/playlists.types'));
    }


    public function afterCreate()
    {
        $this->savePlaylistItems();
    }


    public function beforeUpdate()
    {
        $this->record->updated_at = date('Y-m-d H:i:s');
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
        foreach ($items as $key => $item) {
            $i              = new PlaylistItem();
            $i->playlist_id = $this->record->id;
            $i->type        = ( isset($item->type) ? $item->type : $this->getType($item) );

            $transition = Transition::where('identifier', $item->transition_identifier)->first();

            if (isset($item->overwrite_slide_type) && $item->overwrite_slide_type != '') {
                $i->type = $item->overwrite_slide_type;
            }

            $i->duration             = $item->duration;
            $i->transition_id        = ( is_null($transition) ? null : $transition->id );
            $i->transition_duration  = $item->transition_duration;
            $i->is_advanced_manually = $item->is_advanced_manually;
            $i->midi_note            = $item->midi_note;
            $i->callback_hash        = $item->callback_hash;
            $i->callback_delay       = $item->callback_delay;
            $i->metadata             = ( isset($item->metadata) ? $item->metadata : '{}' );
            $i->sort_position        = $key;

            if (property_exists($item, 'slide_type')) {
                $i->slide_id   = $item->id;
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


    public static function generatePrizegivingPlaylist($data)
    {
        ini_set('max_execution_time', 1200);
        // 1. find out if we have an existing playlist and delete it
        $playlists = Playlist::where('name', 'Prizegiving: Actual prizegiving with winners')->get();
        foreach ($playlists as $playlist) {
            foreach ($playlist->items as $item) {
                if ($item->slide != null) {
                    $item->slide->delete();
                }
            }
            $playlist->delete();
        }

        // 2. create a slide category for this competition in case it does not exist yet
        $category = Category::where('scope', 'slides')->where('name', 'Prizegiving (actual)')->first();
        if (is_null($category)) {
            $rootNode = Category::where('scope', 'slides')->where('_lft', 1)->first();
            if (is_null($rootNode)) {
                die("Root node for slide category tree does not exist");
            }
            $c        = new Category();
            $c->scope = 'slides';
            $c->name  = 'Prizegiving (actual)';
            $rootNode->appendNode($c);
            $category = Category::where('scope', 'slides')->where('name', 'Prizegiving (actual)')->first();
        }

        // 4. create playlist
        $playlist = Playlist::where('name', 'Prizegiving: Actual prizegiving with winners')->first();
        if (is_null($playlist)) {
            $playlist = new Playlist();
        }
        $playlist->name = 'Prizegiving: Actual prizegiving with winners';
        $playlist->type = 'video';
        $playlist->save();

        // 3. save slides
        $count    = 0;
        $slideIds = [];
        foreach (Arr::get($data, 'slide', []) as $slideName => $definitions) {

            $count++;
            $type                 = Arr::get($data, 'type.' . $slideName);
            $name                 = Arr::get($data, 'name.' . $slideName);
            $meta                 = Arr::get($data, 'meta.' . $slideName, '{}');
            $slideType            = config('partymeister-competitions-slides.' . $type . '.slide_type', 'default');
            $midiNote             = config('partymeister-competitions-slides.' . $type . '.midi_note', 0);
            $transitionIdentifier = config('partymeister-competitions-slides.' . $type . '.transition', 0);
            $transitionDuration   = config('partymeister-competitions-slides.' . $type . '.transition_duration', 2000);
            $duration             = config('partymeister-competitions-slides.' . $type . '.duration', 20);
            $isAdvancedManually   = config('partymeister-competitions-slides.' . $type . '.is_advanced_manually', true);

            $transition = Transition::where('identifier', $transitionIdentifier)->first();

            $callback = null;

            switch ($type) {
                case 'comingup':
                    $callback = CallbackHelper::prizegivingStarts();
                    break;
            }

            $s                      = new Slide();
            $s->category_id         = $category->id;
            $s->name                = $name;
            $s->slide_type          = $slideType;
            $s->definitions         = $definitions;
            $s->cached_html_preview = Arr::get($data, 'cached_html_preview.' . $slideName, '');
            $s->cached_html_final   = Arr::get($data, 'cached_html_final.' . $slideName, '');

            $s->save();

            $s->addMedia(public_path() . '/images/generating-preview.png')
              ->preservingOriginal()
              ->withCustomProperties([ 'generating' => true ])
              ->toMediaCollection('preview', 'media');

            $i                       = new PlaylistItem();
            $i->playlist_id          = $playlist->id;
            $i->type                 = 'image';
            $i->slide_type           = $s->slide_type;
            $i->slide_id             = $s->id;
            $i->is_advanced_manually = $isAdvancedManually;
            $i->midi_note            = $midiNote;
            $i->metadata             = $meta;
            if ( ! is_null($transition)) {
                $i->transition_id = $transition->id;
            }
            $i->transition_duration = $transitionDuration;
            $i->duration            = $duration;
            if ( ! is_null($callback)) {
                $i->callback_hash  = $callback->hash;
                $i->callback_delay = 20;
            }

            $i->sort_position = $count;
            $i->save();

            // 7. generate slides
            // Convert PNG to actual file
            //$pngData = array_get($data, 'final.' . $slideName);
            //$pngData = substr($pngData, 22);
            //file_put_contents(storage_path() . '/final_' . $slideName . '.png', base64_decode($pngData));

            $pngData = Arr::get($data, 'preview.' . $slideName);
            $pngData = substr($pngData, 22);
            file_put_contents(storage_path() . '/preview_' . $slideName . '.png', base64_decode($pngData));

            $s->clearMediaCollection('preview');
            $s->clearMediaCollection('final');
            $s->addMedia(storage_path() . '/preview_' . $slideName . '.png')->toMediaCollection('preview', 'media');
            //$s->addMedia(storage_path() . '/final_' . $slideName . '.png')->toMediaCollection('final', 'media');

            $slideIds[] = $s->id;

            //event(new SlideSaved($s, 'slides'));
        }
        //$slideChunks = array_chunk($slideIds, 10);
        //foreach ($slideChunks as $chunk) {
        //    event(new SlideCollectionSaved(collect($chunk), 'slides'));
        //}
    }


    /**
     * @param $competition
     * @param $data
     */
    public static function generateCompetitionPlaylist($competition, $data)
    {
        ini_set('max_execution_time', 1200);

        // 1. find out if we have an existing playlist and delete it
        $playlists = Playlist::where('name', 'Competition: ' . $competition->name)->get();
        foreach ($playlists as $playlist) {
            foreach ($playlist->items as $item) {
                if ($item->slide != null) {
                    $item->slide->delete();
                }
            }
            $playlist->delete();
        }

        // 2. create a slide category for this competition in case it does not exist yet
        $competitionCategory = Category::where('scope', 'slides')->where('name', 'Competitions')->first();
        if (is_null($competitionCategory)) {
            $rootNode = Category::where('scope', 'slides')->where('_lft', 1)->first();
            if (is_null($rootNode)) {
                die("Root node for slide category tree does not exist");
            }
            $c        = new Category();
            $c->scope = 'slides';
            $c->name  = 'Competitions';
            $rootNode->appendNode($c);
        }
        $category = Category::where('scope', 'slides')->where('name', $competition->name)->first();
        if (is_null($category)) {
            $rootNode        = Category::where('scope', 'slides')->where('name', 'Competitions')->first();
            $category        = new Category();
            $category->scope = 'slides';
            $category->name  = $competition->name;
            $rootNode->appendNode($category);
            $category->refresh();
        }

        // 4. create playlist
        $playlist                 = new Playlist();
        $playlist->name           = 'Competition: ' . $competition->name;
        $playlist->type           = 'video';
        $playlist->is_competition = true;
        $playlist->save();

        // 3. save slides
        $count = 0;
        foreach (Arr::get($data, 'slide', []) as $slideName => $definitions) {
            $count++;
            $type                 = Arr::get($data, 'type.' . $slideName);
            $name                 = Arr::get($data, 'name.' . $slideName);
            $id                   = Arr::get($data, 'id.' . $slideName, null);
            $slideType            = config('partymeister-competitions-slides.' . $type . '.slide_type', 'default');
            $midiNote             = config('partymeister-competitions-slides.' . $type . '.midi_note', 0);
            $transitionIdentifier = config('partymeister-competitions-slides.' . $type . '.transition', 5);
            $transitionDuration   = config('partymeister-competitions-slides.' . $type . '.transition_duration', 2000);
            $duration             = config('partymeister-competitions-slides.' . $type . '.duration', 20);
            $isAdvancedManually   = config('partymeister-competitions-slides.' . $type . '.is_advanced_manually', true);

            $transition = Transition::where('identifier', $transitionIdentifier)->first();

            $callback = null;

            switch ($type) {
                case 'comingup':
                    $callback = CallbackHelper::competitionStarts($competition);
                    break;
                case 'entry':
                    if ( ! is_null($id)) {
                        $entry = Entry::find($id);
                        if ( ! is_null($entry)) {
                            $callback = CallbackHelper::livevoting($entry);
                        }
                    }
                    break;
                case 'end':
                    $callback = CallbackHelper::competitionEnds($competition);
                    break;
            }

            switch ($type) {
                case 'comingup':
                case 'now':
                case 'end':
                case 'entry':
                case 'participants':
                    $s                      = new Slide();
                    $s->category_id         = $category->id;
                    $s->name                = $name;
                    $s->slide_type          = $slideType;
                    $s->definitions         = $definitions;
                    $s->cached_html_preview = Arr::get($data, 'cached_html_preview.' . $slideName, '');
                    $s->cached_html_final   = Arr::get($data, 'cached_html_final.' . $slideName, '');

                    $s->save();

                    $s->addMedia(public_path() . '/images/generating-preview.png')
                      ->preservingOriginal()
                      ->withCustomProperties([ 'generating' => true ])
                      ->toMediaCollection('preview', 'media');

                    $i                       = new PlaylistItem();
                    $i->playlist_id          = $playlist->id;
                    $i->type                 = 'image';
                    $i->slide_type           = $s->slide_type;
                    $i->slide_id             = $s->id;
                    $i->is_advanced_manually = $isAdvancedManually;
                    $i->midi_note            = $midiNote;
                    if ( ! is_null($transition)) {
                        $i->transition_id = $transition->id;
                    }
                    $i->transition_duration = $transitionDuration;
                    $i->duration            = $duration;
                    if ( ! is_null($callback)) {
                        $i->callback_hash  = $callback->hash;
                        $i->callback_delay = 20;
                    }

                    $i->sort_position = $count;
                    $i->save();

                    // 7. generate slides
                    // Convert PNG to actual file
                    //$pngData = array_get($data, 'final.' . $slideName);
                    //$pngData = substr($pngData, 22);
                    //file_put_contents(storage_path() . '/final_' . $slideName . '.png', base64_decode($pngData));

                    $pngData = Arr::get($data, 'preview.' . $slideName);
                    $pngData = substr($pngData, 22);
                    file_put_contents(storage_path() . '/preview_' . $slideName . '.png', base64_decode($pngData));

                    $s->clearMediaCollection('preview');
                    $s->clearMediaCollection('final');
                    $s->addMedia(storage_path() . '/preview_' . $slideName . '.png')
                      ->toMediaCollection('preview', 'media');
                    //$s->addMedia(storage_path() . '/final_' . $slideName . '.png')->toMediaCollection('final', 'media');

//                    event(new SlideSaved($s, 'slides'));
                    break;
                case 'video_1':
                case 'video_2':
                case 'video_3':
                    $d = json_decode($definitions, true);

                    //// Get video duration
                    //$file = File::find($d['file_id']);
                    //if (!is_null($file))
                    //{
                    //	$ffmpeg = FFProbe::create([
                    //		'ffmpeg.binaries' => config('medialibrary.ffmpeg_binaries'),
                    //		'ffprobe.binaries' => config('medialibrary.ffprobe_binaries'),
                    //	]);
                    //	$duration = $ffmpeg->format($file->getFirstMedia('file')
                    //		->getPath())// extracts file informations
                    //	->get('duration');             // returns the duration property
                    //}

                    // Load file and check mime type
                    $file = File::find($d['file_id']);
                    if (is_null($file)) {
                        break;
                    }

                    if ($file->media()->first() != null && $file->media()->first()->mime_type == 'video/mp4') {
                        $type = 'video';
                    } else {
                        $type = 'image';
                    }

                    $i                       = new PlaylistItem();
                    $i->playlist_id          = $playlist->id;
                    $i->type                 = $type;
                    $i->is_advanced_manually = $isAdvancedManually;
                    $i->midi_note            = $midiNote;
                    if ( ! is_null($transition)) {
                        $i->transition_id = $transition->id;
                    }
                    $i->transition_duration = $transitionDuration;
                    $i->duration            = $duration;
                    $i->sort_position       = $count;
                    $i->save();

                    // Create file association
                    $fa             = new FileAssociation();
                    $fa->file_id    = $d['file_id'];
                    $fa->model_type = get_class($i);
                    $fa->model_id   = $i->id;
                    $fa->identifier = 'playlist_item';
                    $fa->save();
                    break;
            }
        }
    }


    protected function getType($item)
    {
        if (in_array($item->file->mime_type, [
            'image/png',
            'image/jpg',
            'image/jpeg',
        ])) {
            return 'image';
        }

        if (in_array($item->file->mime_type, [ 'video/mp4' ])) {
            return 'video';
        }

        return '';
    }
}
