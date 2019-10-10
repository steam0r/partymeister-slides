<?php

namespace Partymeister\Slides\Forms\Backend;

use Kris\LaravelFormBuilder\Form;

/**
 * Class SlideClientForm
 * @package Partymeister\Slides\Forms\Backend
 */
class SlideClientForm extends Form
{

    /**
     * @return mixed|void
     */
    public function buildForm()
    {
        $this->add(
            'name',
            'text',
            [ 'label' => trans('partymeister-slides::backend/slide_clients.name'), 'rules' => 'required' ]
        )
             ->add('type', 'select', [
                 'label'   => trans('partymeister-slides::backend/slide_clients.type'),
                 'choices' => (trans('partymeister-slides::backend/slide_clients.types'))
             ])
             ->add('ip_address', 'text', [ 'label' => trans('partymeister-slides::backend/slide_clients.ip_address') ])
             ->add('port', 'text', [ 'label' => trans('partymeister-slides::backend/slide_clients.port') ])
             ->add(
                 'sort_position',
                 'text',
                 [ 'label' => trans('partymeister-slides::backend/slide_clients.sort_position') ]
             )
             ->add('submit', 'submit', [
                 'attr'  => [ 'class' => 'btn btn-primary' ],
                 'label' => trans('partymeister-slides::backend/slide_clients.save')
             ]);
    }
}
