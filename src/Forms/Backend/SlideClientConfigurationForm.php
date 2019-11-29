<?php

namespace Partymeister\Slides\Forms\Backend;

use Kris\LaravelFormBuilder\Form;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

/**
 * Class SlideClientForm
 * @package Partymeister\Slides\Forms\Backend
 */
class SlideClientConfigurationForm extends Form implements HasMedia
{
    use HasMediaTrait;

    /**
     * @return mixed|void
     */
    public function buildForm()
    {
        $this->add(
            'prizegiving_bar_color',
            'colorpicker',
            [ 'label' => trans('partymeister-slides::backend/slide_clients.prizegiving_bar_color') ]
        )
            ->add(
                'prizegiving_bar_blink_color',
                'colorpicker',
                [ 'label' => trans('partymeister-slides::backend/slide_clients.prizegiving_bar_blink_color') ]
            );

        foreach(trans('partymeister-slides::backend/slides.slide_types') as $type => $name) {
            $this->add(
                'fragment_'.$type,
                'textarea',
                [ 'label' => trans('partymeister-slides::backend/slide_clients.fragment', ['type' => $name]) ]
            );
//            $this->add(
//                'copy_from_'.$type,
//                'select',
//                [ 'label' => trans('partymeister-slides::backend/slide_clients.copy_from') ]
//            );
        }
    }
}
