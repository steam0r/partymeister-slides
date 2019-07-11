<?php

namespace Partymeister\Slides\Http\Controllers\Backend;

use Illuminate\Database\Eloquent\Model;
use Motor\Backend\Http\Controllers\Controller;

use Partymeister\Slides\Models\Slide;
use Partymeister\Slides\Http\Requests\Backend\SlideRequest;
use Partymeister\Slides\Models\SlideTemplate;
use Partymeister\Slides\Services\SlideService;
use Partymeister\Slides\Grids\SlideGrid;
use Partymeister\Slides\Forms\Backend\SlideForm;

use Kris\LaravelFormBuilder\FormBuilderTrait;

class SlidesController extends Controller
{

    use FormBuilderTrait;


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $grid = new SlideGrid(Slide::class);

        $service = SlideService::collection($grid);
        $grid->setFilter($service->getFilter());
        $paginator = $service->getPaginator();

        return view('partymeister-slides::backend.slides.index', compact('paginator', 'grid'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Model $record)
    {
        if ($record instanceof SlideTemplate) {
            $record->slide_template_id = $record->id;
        }
        $form = $this->form(SlideForm::class, [
            'method'  => 'POST',
            'route'   => 'backend.slides.store',
            'enctype' => 'multipart/form-data',
            'model'   => $record,
        ]);

        $motorShowRightSidebar = true;

        return view('partymeister-slides::backend.slides.create', compact('form', 'motorShowRightSidebar'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(SlideRequest $request)
    {
        $form = $this->form(SlideForm::class);

        // It will automatically use current request, get the rules, and do the validation
        if ( ! $form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        SlideService::createWithForm($request, $form);

        flash()->success(trans('partymeister-slides::backend/slides.created'));

        return redirect('backend/slides');
    }


    /**
     * @param SlideTemplate $record
     */
    public function duplicate(Slide $record)
    {
        $newRecord       = $record->replicate();
        $newRecord->name = 'Duplicate of ' . $newRecord->name;

        return $this->create($newRecord);
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show(SlideRequest $request, Slide $record)
    {
        $preview = $request->get('preview', 'false');

        $placeholderData = json_encode([
            'wurst'    => 'Schinken',
            'schinken' => 'uahahahahha!'
        ]);

        return view('partymeister-slides::backend.slide_templates.show',
            compact('record', 'preview', 'placeholderData'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Slide $record)
    {
        $form = $this->form(SlideForm::class, [
            'method'  => 'PATCH',
            'url'     => route('backend.slides.update', [ $record->id ]),
            'enctype' => 'multipart/form-data',
            'model'   => $record
        ]);

        $motorShowRightSidebar = true;

        return view('partymeister-slides::backend.slides.edit', compact('form', 'motorShowRightSidebar'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(SlideRequest $request, Slide $record)
    {
        $form = $this->form(SlideForm::class);

        // It will automatically use current request, get the rules, and do the validation
        if ( ! $form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        SlideService::updateWithForm($record, $request, $form);

        flash()->success(trans('partymeister-slides::backend/slides.updated'));

        return redirect('backend/slides');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Slide $record)
    {
        SlideService::delete($record);

        flash()->success(trans('partymeister-slides::backend/slides.deleted'));

        return redirect('backend/slides');
    }
}
