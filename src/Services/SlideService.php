<?php

namespace Partymeister\Slides\Services;

use Motor\Backend\Models\Category;
use Motor\Core\Filter\Renderers\SelectRenderer;
use Partymeister\Slides\Helpers\Browsershot;
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
        $filnameForPreview = base_path().'/storage/app/temp/slide_preview_'.$this->record->id.'.png';
        $filenameForFinal = base_path().'/storage/app/temp/slide_final_'.$this->record->id.'.png';

        Browsershot::url(url('/backend/slides/' . $this->record->id))
            ->waitUntilNetworkIdle()
            ->windowSize(1920, 1080)
            //->debug()
            //->fit(Manipulations::FIT_CONTAIN, 1920, 1080)
            ->save($filenameForFinal);

        Browsershot::url(url('/backend/slides/' . $this->record->id.'?preview=true'))
            ->waitUntilNetworkIdle()
            ->windowSize(1920, 1080)
            //->debug()
            //->fit(Manipulations::FIT_CONTAIN, 1920, 1080)
            ->save($filnameForPreview);

        $this->record->clearMediaCollection('preview');
        $this->record->clearMediaCollection('final');
        $this->record->addMedia($filnameForPreview)->toMediaCollection('preview', 'media');
        $this->record->addMedia($filenameForFinal)->toMediaCollection('final', 'media');
    }
}
