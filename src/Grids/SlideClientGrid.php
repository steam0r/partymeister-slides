<?php

namespace Partymeister\Slides\Grids;

use Motor\Backend\Grid\Grid;

class SlideClientGrid extends Grid
{

    protected function setup()
    {
        $this->addColumn('name', trans('partymeister-slides::backend/slide_clients.name'), true);
        $this->addColumn('ip_address', trans('partymeister-slides::backend/slide_clients.ip_address'), true);
        $this->addColumn('port', trans('partymeister-slides::backend/slide_clients.port'), true);
        $this->addColumn('sort_position', trans('partymeister-slides::backend/slide_clients.sort_position'), true);
        $this->setDefaultSorting('sort_position', 'ASC');
        $this->addEditAction(trans('motor-backend::backend/global.edit'), 'backend.slide_clients.edit');
        $this->addDeleteAction(trans('motor-backend::backend/global.delete'), 'backend.slide_clients.destroy');
    }
}
