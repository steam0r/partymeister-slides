<?php

namespace Partymeister\Slides\Forms\Backend;

use Kris\LaravelFormBuilder\Form;

class SlideClientForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('name', 'text', ['label' => trans('partymeister-slides::backend/slide_clients.name'), 'rules' => 'required'])
            ->add('ip_address', 'text', ['label' => trans('partymeister-slides::backend/slide_clients.ip_address'), 'rules' => 'required'])
            ->add('port', 'text', ['label' => trans('partymeister-slides::backend/slide_clients.port'), 'rules' => 'required'])
            ->add('sort_position', 'text', ['label' => trans('partymeister-slides::backend/slide_clients.sort_position')])
            ->add('submit', 'submit', ['attr' => ['class' => 'btn btn-primary'], 'label' => trans('partymeister-slides::backend/slide_clients.save')]);
    }
}
