<?php

namespace Partymeister\Slides\Transformers;

use League\Fractal;
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
    protected $availableIncludes = [];


    /**
     * Transform record to array
     *
     * @param Playlist $record
     *
     * @return array
     */
    public function transform(Playlist $record)
    {
        return [
            'id'         => (int) $record->id,
            'type'       => $record->type,
            'name'       => $record->name,
            'created_at' => $record->created_at,
            'updated_at' => $record->updated_at
        ];
    }
}
