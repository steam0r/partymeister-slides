<?php

namespace Partymeister\Slides\Transformers;

use League\Fractal;
use Partymeister\Slides\Models\SlideClient;

class SlideClientTransformer extends Fractal\TransformerAbstract
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
     * @param SlideClient $record
     *
     * @return array
     */
    public function transform(SlideClient $record)
    {
        return [
            'id'        => (int) $record->id
        ];
    }
}
