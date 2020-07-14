<?php
namespace Partymeister\Slides\Transformers\SlideTypes;

use Partymeister\Competitions\Models\Competition;
use Partymeister\Competitions\Models\Entry;
use Partymeister\Competitions\Transformers\EntryTransformer;
use Partymeister\Slides\Models\Slide;
use Partymeister\Slides\Transformers\SlideTransformer;

class CompoSlideTransformer extends SlideTransformer
{
    public function transform(Slide $record) {
        $data = [];
        $definitions = json_decode($record->definitions);
        $competition = Competition::where('name', $record->category->name)->first();
        if($competition) {
            foreach($definitions->elements as $key => $element) {
                if($element->properties->placeholder == "<<sort_position_prefixed>>") {
                    $sortPosition = (int) ltrim( $element->properties->content, "0");
                    /** @var Entry $entry */
                    $entry = Entry::where('competition_id', (int) $competition->id)->where('sort_position', $sortPosition)->first();
                    if($entry) {
                        $entryTransformer = new EntryTransformer();
                        $data['entry'] = $entryTransformer->transform($entry);
                    }
                }
            }
        }
        return $data;
    }
}
