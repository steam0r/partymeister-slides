<?php
namespace Partymeister\Slides\Transformers\SlideTypes;

use Partymeister\Slides\Models\Slide;
use Partymeister\Slides\Transformers\SlideTransformer;

class AnnounceSlideTransformer extends SlideTransformer
{
    public function transform(Slide $record) {
        return [];
    }
}
