<?php

namespace Partymeister\Slides\Transformers;

use League\Fractal;
use Motor\Backend\Helpers\MediaHelper;
use Partymeister\Slides\Models\Slide;
use Partymeister\Slides\Transformers\SlideTypes\AnnounceImportantSlideTransformer;
use Partymeister\Slides\Transformers\SlideTypes\AnnounceSlideTransformer;
use Partymeister\Slides\Transformers\SlideTypes\ComingUpSlideTransformer;
use Partymeister\Slides\Transformers\SlideTypes\CompoEndSlideTransformer;
use Partymeister\Slides\Transformers\SlideTypes\CompoSlideTransformer;
use Partymeister\Slides\Transformers\SlideTypes\PrizegivingBarsSlideTransformer;
use Partymeister\Slides\Transformers\SlideTypes\PrizegivingWinnersSlideTransformer;

/**
 * Class SlideTransformer
 * @package Partymeister\Slides\Transformers
 */
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
     * @param  Slide  $record
     *
     * @return array
     */
    public function transform(Slide $record)
    {
        $slide = [
            'id' => (int)$record->id,
            'name' => $record->name,
            'slide_type' => $record->slide_type,
            'category_id' => $record->category_id,
            'category_name' => (!is_null($record->category) ? $record->category->name : ''),
            'file' => MediaHelper::getFileInformation($record, 'preview', false, ['preview', 'thumb']),
            'file_final' => MediaHelper::getFileInformation($record, 'final', false, ['preview', 'thumb']),
            'cached_html_preview' => $record->cached_html_preview,
            'cached_html_final' => $record->cached_html_final,
            'additionals' => $this->getAdditionalSlideData($record),
        ];

        return $slide;
    }

    private function getAdditionalSlideData(Slide $record)
    {
        $additional = [];
        switch ($record->slide_type) {
            case 'comingup':
                $transformer = new ComingUpSlideTransformer();
                $additional = $transformer->transform($record);
                break;
            case 'compo':
                $transformer = new CompoSlideTransformer();
                $additional = $transformer->transform($record);
                break;
            case 'end':
                $transformer = new CompoEndSlideTransformer();
                $additional = $transformer->transform($record);
                break;
            case 'siegmeister_bars':
                $transformer = new PrizegivingBarsSlideTransformer();
                $additional = $transformer->transform($record);
                break;
            case 'siegmeister_winners':
                $transformer = new PrizegivingWinnersSlideTransformer();
                $additional = $transformer->transform($record);
                break;
            case 'announce':
                $transformer = new AnnounceSlideTransformer();
                $additional = $transformer->transform($record);
                break;
            case 'announce_important':
                $transformer = new AnnounceImportantSlideTransformer();
                $additional = $transformer->transform($record);
                break;
            default:
                break;
        }

        return $additional;
    }
}
