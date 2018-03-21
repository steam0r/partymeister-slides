<?php

namespace Partymeister\Slides\Transformers;

use League\Fractal;
use Partymeister\Slides\Models\SlideTemplate;

class SlideTemplateTransformer extends Fractal\TransformerAbstract
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
     * @param SlideTemplate $record
     *
     * @return array
     */
    public function transform(SlideTemplate $record)
    {
        return [
            'id'        => (int) $record->id
        ];
    }
}
