<?php

namespace Partymeister\Slides\Transformers;

use Illuminate\Support\Arr;
use League\Fractal;
use League\Fractal\Manager;
use League\Fractal\Serializer\ArraySerializer;
use Partymeister\Competitions\Models\Competition;
use Partymeister\Competitions\Transformers\CompetitionTransformer;
use Partymeister\Competitions\Transformers\Entry\JsonTransformer;
use Partymeister\Slides\Models\Playlist;

/**
 * Class PlaylistTransformer
 * @package Partymeister\Slides\Transformers
 */
class PlaylistTransformer extends Fractal\TransformerAbstract
{

    /**
     * List of resources possible to include
     *
     * @var array
     */
    //protected $availableIncludes = ['items'];
    //
    //protected $defaultIncludes = [ 'items' ];

    /**
     * Transform record to array
     *
     * @param Playlist $record
     *
     * @return array
     */
    public function transform(Playlist $record)
    {
        $data = [
            'id'         => (int) $record->id,
            'type'       => $record->type,
            'name'       => $record->name,
            'is_competition' => $record->is_competition,
            'competition_id' => $record->competition_id,
            'created_at' => $record->created_at,
            'updated_at' => $record->updated_at
        ];
        if($record->is_competition && $record->competition_id) {
            $competition = Competition::where('id', $record->competition_id)->first();
            if ($competition) {
                $manager = new Manager();
                $resource = $this->item($competition, new CompetitionTransformer());
                $entry = $manager->createData($resource)->toArray();
                $entry = Arr::get($entry, 'data');
                $data['competition'] = $entry;
                $data['entries'] = [];
                foreach ($competition->entries()->where('status', 1)->orderBy('sort_position', 'ASC')->get() as $entry) {
                    $resource = $this->item($entry, new JsonTransformer());
                    $entry = $manager->createData($resource)->toArray();
                    $entry = Arr::get($entry, 'data');
                    $data['entries'][] = $entry;
                }
            }
        }
        return $data;
    }

    /**
     * @param  Playlist  $record
     * @return Fractal\Resource\Collection
     */
    public function includeItems(Playlist $record)
    {
        if (! is_null($record->items)) {
            return $this->collection($record->items, new PlaylistItemTransformer());
        }
    }
}
