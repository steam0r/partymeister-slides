<?php

namespace Partymeister\Slides\Grids;

use Motor\Backend\Grid\Grid;
use Motor\Backend\Grid\Renderers\FileRenderer;
use Motor\Backend\Grid\Renderers\TranslateRenderer;

class SlideGrid extends Grid
{

    protected function setup()
    {
        $this->addColumn('preview', trans('motor-media::backend/files.file'))->renderer(FileRenderer::class, ['file' => 'preview']);
        $this->addColumn('name', trans('motor-backend::backend/global.name'), true);
        $this->addColumn('slide_type', trans('partymeister-slides::backend/slides.slide_type'))->renderer(TranslateRenderer::class, ['file' => 'partymeister-slides::backend/slides.slide_types']);
        $this->addColumn('category.name', trans('motor-backend::backend/categories.category'));
        $this->setDefaultSorting('id', 'DESC');

        $this->addEditAction(trans('motor-backend::backend/global.edit'), 'backend.slides.edit');
        $this->addDuplicateAction(trans('motor-backend::backend/global.duplicate'), 'backend.slides.duplicate')->needsPermissionTo('slides.write');
        $this->addDeleteAction(trans('motor-backend::backend/global.delete'), 'backend.slides.destroy');
    }
}
