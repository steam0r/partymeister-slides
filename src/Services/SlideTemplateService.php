<?php

namespace Partymeister\Slides\Services;

use Motor\Core\Filter\Renderers\SelectRenderer;
use Partymeister\Slides\Events\SlideSaved;
use Partymeister\Slides\Events\SlideTemplateSaved;
use Partymeister\Slides\Jobs\GenerateSlide;
use Partymeister\Slides\Models\SlideTemplate;
use Motor\Backend\Services\BaseService;
use Spatie\Browsershot\Browsershot;
use Spatie\Image\Manipulations;

class SlideTemplateService extends BaseService
{

    protected $model = SlideTemplate::class;


    public function filters()
    {
        $this->filter->add(new SelectRenderer('template_for'))
                     ->setOptionPrefix(trans('partymeister-slides::backend/slide_templates.template_for'))
                     ->setEmptyOption('-- ' . trans('partymeister-slides::backend/slide_templates.template_for') . ' --')
                     ->setOptions(trans('partymeister-slides::backend/slide_templates.template_for_types'));
    }

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

        GenerateSlide::dispatch($this->record, 'slide_templates')
            ->onConnection('sync');
//        event(new SlideSaved($this->record, 'slide_templates'));
    }
}
