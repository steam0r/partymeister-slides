<?php

namespace Partymeister\Slides\Services;

use Illuminate\Support\Arr;
use Motor\Backend\Models\Category;
use Motor\Core\Filter\Renderers\SelectRenderer;
use Partymeister\Slides\Events\SlideSaved;
use Partymeister\Slides\Helpers\Browsershot;
use Partymeister\Slides\Jobs\GenerateSlide;
use Partymeister\Slides\Models\Slide;
use Motor\Backend\Services\BaseService;

class SlideService extends BaseService
{

    protected $model = Slide::class;

    public function filters()
    {
        $this->filter->add(new SelectRenderer('slide_type'))->setEmptyOption('-- '.trans('partymeister-slides::backend/slides.slide_type').' --')->setOptions(trans('partymeister-slides::backend/slides.slide_types'));

        $categories = Category::where('scope', 'slides')->where('_lft', '>', 1)->orderBy('_lft', 'ASC')->pluck('name', 'id');
        $this->filter->add(new SelectRenderer('category_id'))->setEmptyOption('-- '.trans('motor-backend::backend/categories.categories').' --')->setOptions($categories);
    }

    public function beforeCreate()
    {
        $this->data['definitions'] = stripslashes($this->request->get('definitions'));
        if ($this->request->get('slide_template_id') == '') {
            $this->data['slide_template_id'] = null;
        }
    }

    public function beforeUpdate()
    {
        if ($this->request->get('slide_template_id') == '') {
            $this->data['slide_template_id'] = null;
        }
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
        // Convert PNG to actual file
        $pngData = Arr::get($this->data, 'png_preview');
        $pngData = substr($pngData, 22);
        file_put_contents(storage_path() . '/preview_' . $this->record->id . '.png', base64_decode($pngData));

        //$pngData = array_get($this->data, 'png_final');
        //$pngData = substr($pngData, 22);
        //file_put_contents(storage_path() . '/final_' . $this->record->id . '.png', base64_decode($pngData));

        $this->record->clearMediaCollection('preview');
        $this->record->clearMediaCollection('final');

        $this->record->addMedia(storage_path() . '/preview_' . $this->record->id . '.png')->toMediaCollection('preview', 'media');
        //$this->record->addMedia(storage_path() . '/final_' . $this->record->id . '.png')->toMediaCollection('final', 'media');

        //GenerateSlide::dispatch($this->record, 'slides')
        //    ->onConnection('sync');
//        event(new SlideSaved($this->record, 'slides'));
    }
}
