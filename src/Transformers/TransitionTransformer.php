<?php

namespace Partymeister\Slides\Transformers;

use League\Fractal;
use Partymeister\Slides\Models\Transition;

/**
 * Class TransitionTransformer
 * @package Partymeister\Slides\Transformers
 */
class TransitionTransformer extends Fractal\TransformerAbstract
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
     * @param Transition $record
     *
     * @return array
     */
    public function transform(Transition $record)
    {
        return [
            'id'               => (int) $record->id,
            'name'             => $record->name,
            'identifier'       => $record->identifier,
            'default_duration' => $record->default_duration
        ];
    }
}
