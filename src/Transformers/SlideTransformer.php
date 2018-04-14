<?php

namespace Partymeister\Slides\Transformers;

use League\Fractal;
use Motor\Backend\Helpers\MediaHelper;
use Partymeister\Slides\Models\Slide;

class SlideTransformer extends Fractal\TransformerAbstract
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
     * @param Slide $record
     *
     * @return array
     */
    public function transform(Slide $record)
    {
        return [
            'id'                  => (int) $record->id,
            'name'                => $record->name,
            'slide_type'          => $record->slide_type,
            'category_id'         => $record->category_id,
            'category_name'       => ( ! is_null($record->category) ? $record->category->name : '' ),
            'file'                => MediaHelper::getFileInformation($record, 'preview', false, [ 'preview', 'thumb' ]),
            'file_final'          => MediaHelper::getFileInformation($record, 'final', false, [ 'preview', 'thumb' ]),
            'cached_html_preview' => $record->cached_html_preview,
            'cached_html_final'   => $record->cached_html_final,
        ];
    }
}
