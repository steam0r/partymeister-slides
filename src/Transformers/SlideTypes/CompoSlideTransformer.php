<?php

namespace Partymeister\Slides\Transformers\SlideTypes;

use Illuminate\Support\Arr;
use Partymeister\Competitions\Models\Competition;
use Partymeister\Competitions\Models\Entry;
use Partymeister\Competitions\Transformers\Entry\JsonTransformer;
use Partymeister\Slides\Models\Slide;

class CompoSlideTransformer extends AbstractSlideTransformer
{
    public function transform(Slide $record)
    {
        $data = parent::transform($record);
        $definitions = json_decode($record->definitions);
        if($record->category->competition_id) {
            $competition = Competition::where('id', $record->category->competition_id)->first();
        }else{
            // fallback to association by name if compoid is not set
            $competition = Competition::where('name', $record->category->name)->first();
        }
        if ($competition) {
            foreach ($definitions->elements as $key => $element) {
                if ($element->properties->placeholder == "<<sort_position_prefixed>>") {
                    $sortPosition = (int)ltrim($element->properties->content, "0");
                    /** @var Entry $entry */
                    $entry = Entry::where('competition_id', (int)$competition->id)
                        ->where('sort_position', $sortPosition)
                        ->first();
                    if ($entry) {
                        $resource = $this->transformItem($entry, JsonTransformer::class);
                        $entry = $this->fractal->createData($resource)->toArray();
                        $entry = Arr::get($entry, 'data');
                        $data['entry'] = $entry;
                    }
                }
            }
        }

        return $data;
    }
}
