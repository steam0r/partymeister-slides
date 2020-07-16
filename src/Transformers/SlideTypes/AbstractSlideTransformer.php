<?php


namespace Partymeister\Slides\Transformers\SlideTypes;

use League\Fractal;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Partymeister\Slides\Models\Slide;

class AbstractSlideTransformer extends Fractal\TransformerAbstract
{

    /**
     * @var Fractal\Manager
     */
    protected $fractal;

    public function __construct()
    {
        $this->fractal = new Fractal\Manager();
    }

    public function transform(Slide $record)
    {
        $definitions = json_decode($record->definitions);
        $data = [];
        $slidesData = $definitions->elements;
        foreach ($slidesData as $slideData) {
            unset($slideData->moveable);
        }
        $data['id'] = $definitions->id;
        $data['type'] = $definitions->type;
        $data['elements'] = $slidesData;
        return ['slidesData' => $data];
    }

    /**
     * @param        $record
     * @param        $transformer
     * @param  string  $includes
     * @return Item
     */
    protected function transformItem($record, $transformer, $includes = '')
    {
        $this->fractal->parseIncludes($includes);

        return new Item($record, (new $transformer));
    }

    /**
     * @param        $collection
     * @param        $transformer
     * @param  string  $includes
     * @return Collection
     */
    protected function transformCollection($collection, $transformer, $includes = '')
    {
        $this->fractal->parseIncludes($includes);

        return new Collection($collection, (new $transformer));
    }
}
