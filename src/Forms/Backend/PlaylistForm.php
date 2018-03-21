<?php

namespace Partymeister\Slides\Forms\Backend;

use Kris\LaravelFormBuilder\Form;

class PlaylistForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('name', 'text', ['label' => trans('motor-backend::backend/global.name'), 'rules' => 'required'])
            ->add('playlist_items', 'hidden', ['attr' => ['id' => 'playlist-items']])
            ->add('type', 'select', ['label' => trans('partymeister-slides::backend/playlists.type'), 'rules' => 'required', 'choices' => ['video' => trans('partymeister-slides::backend/playlists.types.video'), 'prizegiving' => trans('partymeister-slides::backend/playlists.types.prizegiving')]])
            ->add('submit', 'submit', ['attr' => ['class' => 'btn btn-primary playlist-submit'], 'label' => trans('partymeister-slides::backend/playlists.save')]);
    }
}
