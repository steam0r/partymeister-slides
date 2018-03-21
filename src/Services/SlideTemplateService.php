<?php

namespace Partymeister\Slides\Services;

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
        $filnameForPreview = base_path().'/storage/app/temp/slide_template_preview_'.$this->record->id.'.png';
        $filenameForFinal = base_path().'/storage/app/temp/slide_template_final_'.$this->record->id.'.png';

        Browsershot::url(url('/backend/slide_templates/' . $this->record->id))
            ->waitUntilNetworkIdle()
            ->windowSize(1920, 1080)
            //->fit(Manipulations::FIT_CONTAIN, 1920, 1080)
            ->save($filenameForFinal);

        Browsershot::url(url('/backend/slide_templates/' . $this->record->id.'?preview=true'))
            ->waitUntilNetworkIdle()
            ->windowSize(1920, 1080)
            //->fit(Manipulations::FIT_CONTAIN, 1920, 1080)
            ->save($filnameForPreview);

        $this->record->clearMediaCollection('preview');
        $this->record->clearMediaCollection('final');
        $this->record->addMedia($filnameForPreview)->toMediaCollection('preview', 'media');
        $this->record->addMedia($filenameForFinal)->toMediaCollection('final', 'media');
    }
}
