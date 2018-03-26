<?php

namespace Partymeister\Slides\Services;

use Partymeister\Slides\Events\SlideSaved;
use Partymeister\Slides\Events\SlideTemplateSaved;
use Partymeister\Slides\Models\SlideTemplate;
use Motor\Backend\Services\BaseService;
use Spatie\Browsershot\Browsershot;
use Spatie\Image\Manipulations;

class SlideTemplateService extends BaseService
{

    protected $model = SlideTemplate::class;


    public function beforeCreate()
    {
        $this->data['definitions'] = stripslashes($this->request->get('definitions'));
    }


    public function beforeUpdate()
    {
        $this->beforeCreate();
    }


    public function afterCreate()
    {
        $this->generatePreview();
    }


    public function afterUpdate()
    {
        $this->generatePreview();
    }


    protected function generatePreview()
    {
        $media = $this->record->getFirstMedia('preview');
        if (!is_null($media)) {
            $media->setCustomProperty('generating', true)->save();
        } else {
            $this->record->addMedia(public_path() . '/images/generating-preview.png')
              ->preservingOriginal()
              ->withCustomProperties([ 'generating' => true ])
              ->toMediaCollection('preview', 'media');

        }

        //$this->record->clearMediaCollection('preview');
        //$this->record->clearMediaCollection('final');
        //
        //$this->record
        //     ->addMedia(public_path().'/images/generating-preview.png')
        //     ->preservingOriginal()
        //     ->toMediaCollection('preview', 'media');

        event(new SlideSaved($this->record, 'slide_templates'));
    }
}
