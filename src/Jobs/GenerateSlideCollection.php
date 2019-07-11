<?php

namespace Partymeister\Slides\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Collection;
use Partymeister\Slides\Helpers\Browsershot;
use Partymeister\Slides\Models\Slide;

class GenerateSlideCollection implements ShouldQueue
{

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $slideIds;

    public $namePrefix;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Collection $slideIds, $namePrefix)
    {
        $this->slideIds      = $slideIds;
        $this->namePrefix = $namePrefix;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //foreach ($this->slideIds as $slideId) {
        //    $filenameForPreview = base_path() . '/storage/app/' . $this->namePrefix . '_preview_' . $slideId . '.png';
        //    $filenameForFinal   = base_path() . '/storage/app/' . $this->namePrefix . '_final_' . $slideId . '.png';
        //}

        $command = 'node '.__DIR__.'/../../resources/assets/bin/hack.js \''.json_encode(['slides' => $this->slideIds]).'\'';

        exec($command);

        //dd($result);

        //$this->slide->clearMediaCollection('preview');
        //$this->slide->clearMediaCollection('final');
        //$this->slide->addMedia($filenameForPreview)->toMediaCollection('preview', 'media');
        //$this->slide->addMedia($filenameForFinal)->toMediaCollection('final', 'media');
    }
}
