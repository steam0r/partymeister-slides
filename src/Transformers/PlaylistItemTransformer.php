<?php

namespace Partymeister\Slides\Transformers;

use League\Fractal;
use Partymeister\Slides\Models\PlaylistItem;

/**
 * Class PlaylistItemTransformer
 * @package Partymeister\Slides\Transformers
 */
class PlaylistItemTransformer extends Fractal\TransformerAbstract
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
     * @param PlaylistItem $record
     *
     * @return array
     */
    public function transform(PlaylistItem $record)
    {
        return [
            'id'                    => (int) $record->id,
            'playlist_id'           => (int) $record->playlist_id,
            'type'                  => $record->type,
            'duration'              => $record->duration,
            'transition_id'         => $record->transition_id,
            'transition_identifier' => (! is_null($record->transition) ? $record->transition->identifier : ''),
            'transition_slidemeister_identifier' => (! is_null($record->transition_slidemeister) ? $record->transition_slidemeister->identifier : ''),
            'transition_duration'   => $record->transition_duration,
            'is_advanced_manually'  => (bool) $record->is_advanced_manually,
            'is_muted'              => (bool) $record->is_muted,
            'midi_note'             => (int) $record->midi_note,
            'metadata'              => $record->metadata,
            'callback_hash'         => $record->callback_hash,
            'callback_delay'        => $record->callback_delay
        ];
    }
}
