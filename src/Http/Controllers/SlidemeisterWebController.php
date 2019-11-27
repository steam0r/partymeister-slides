<?php

namespace Partymeister\Slides\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Partymeister\Slides\Models\SlideClient;

/**
 * Class SlidemeisterWebController
 * @package Partymeister\Slides\Http\Controllers
 */
class SlidemeisterWebController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \ReflectionException
     */
    public function index(SlideClient $record)
    {
        $jingles = [];
        foreach ($record->file_associations as $file) {
            $mediaFile = $file->file->getMedia('file')->first();
            if ($mediaFile) {
                $jingles[ $file->identifier ] = $mediaFile->getUrl();
            }
        }

        return view('partymeister-slides::slidemeister-web/index',
            [
                'slideClientId'            => $record->id,
                'jingles'                  => $jingles,
                'prizegivingBarColor'      => Arr::get($record->configuration, 'prizegiving_bar_color'),
                'prizegivingBarBlinkColor' => Arr::get($record->configuration, 'prizegiving_bar_blink_color'),
                'channelPrefix'            => config('cache.prefix')
            ]);
    }
}
