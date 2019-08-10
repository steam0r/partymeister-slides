<?php

namespace Partymeister\Slides\Services;

use Illuminate\Support\Arr;
use Motor\Backend\Services\BaseService;
use Motor\Core\Filter\Renderers\SelectRenderer;
use Partymeister\Slides\Events\SlideTemplateSaved;
use Partymeister\Slides\Models\SlideTemplate;

/**
 * Class SlideTemplateService
 * @package Partymeister\Slides\Services
 */
class SlideTemplateService extends BaseService
{

    /**
     * @var string
     */
    protected $model = SlideTemplate::class;


    public function filters()
    {
        $this->filter->add(new SelectRenderer('template_for'))
                     ->setOptionPrefix(trans('partymeister-slides::backend/slide_templates.template_for'))
                     ->setEmptyOption('-- ' . trans('partymeister-slides::backend/slide_templates.template_for') . ' --')
                     ->setOptions(trans('partymeister-slides::backend/slide_templates.template_for_types'));
    }


    public function beforeUpdate()
    {
        $this->beforeCreate();
    }


    public function beforeCreate()
    {
        $this->data['definitions'] = stripslashes($this->request->get('definitions'));
    }


    public function afterCreate()
    {
        $this->generatePreview();
    }


    protected function generatePreview()
    {
        // Convert PNG to actual file
        $pngData = Arr::get($this->data, 'png_preview');
        $pngData = substr($pngData, 22);
        file_put_contents(storage_path() . '/preview_' . $this->record->id . '.png', base64_decode($pngData));

        $pngData = Arr::get($this->data, 'png_final');
        $pngData = substr($pngData, 22);
        file_put_contents(storage_path() . '/final_' . $this->record->id . '.png', base64_decode($pngData));

        $this->record->clearMediaCollection('preview');
        $this->record->clearMediaCollection('final');

        $this->record->addMedia(storage_path() . '/preview_' . $this->record->id . '.png')
                     ->toMediaCollection('preview', 'media');
        $this->record->addMedia(storage_path() . '/final_' . $this->record->id . '.png')
                     ->toMediaCollection('final', 'media');

//        GenerateSlide::dispatch($this->record, 'slide_templates')
//            ->onConnection('sync');
////        event(new SlideSaved($this->record, 'slide_templates'));
    }


    public function afterUpdate()
    {
        $this->generatePreview();
    }
}
