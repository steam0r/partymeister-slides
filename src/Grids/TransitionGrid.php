<?php

namespace Partymeister\Slides\Grids;

use Motor\Backend\Grid\Grid;

class TransitionGrid extends Grid
{

    protected function setup()
    {
        $this->addColumn('name', trans('motor-backend::backend/global.name'), true);
        $this->addColumn('identifier', trans('partymeister-slides::backend/transitions.identifier'), true);
        $this->addColumn('default_duration', trans('partymeister-slides::backend/transitions.default_duration'), true);
        $this->setDefaultSorting('name', 'ASC');
        $this->addEditAction(trans('motor-backend::backend/global.edit'), 'backend.transitions.edit');
        $this->addDeleteAction(trans('motor-backend::backend/global.delete'), 'backend.transitions.destroy');
    }
}
