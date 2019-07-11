<?php

namespace Partymeister\Slides\Http\Controllers\Backend;

use Motor\Media\Models\File;
use Motor\Media\Services\FileService;
use Partymeister\Slides\Grids\FileGrid;
use Kris\LaravelFormBuilder\FormBuilderTrait;

class FilesController extends \Motor\Media\Http\Controllers\Backend\FilesController
{

    use FormBuilderTrait;


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $grid = new FileGrid(File::class);

        $service = FileService::collection($grid);
        $grid->setFilter($service->getFilter());
        $paginator = $service->getPaginator();

        return view('motor-media::backend.files.index', compact('paginator', 'grid'));
    }
}
