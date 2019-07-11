<?php

namespace Partymeister\Slides\Http\Controllers\Backend;

use Motor\Backend\Http\Controllers\Controller;

use Partymeister\Slides\Models\SlideClient;
use Partymeister\Slides\Http\Requests\Backend\SlideClientRequest;
use Partymeister\Slides\Services\SlideClientService;
use Partymeister\Slides\Grids\SlideClientGrid;
use Partymeister\Slides\Forms\Backend\SlideClientForm;

use Kris\LaravelFormBuilder\FormBuilderTrait;

class SlideClientsController extends Controller
{

    use FormBuilderTrait;


    public function activate(SlideClient $record, SlideClientRequest $request)
    {
        session([ 'screens.active' => $record->id ]);

        return redirect($request->server('HTTP_REFERER'));
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $grid = new SlideClientGrid(SlideClient::class);

        $service = SlideClientService::collection($grid);
        $grid->setFilter($service->getFilter());
        $paginator = $service->getPaginator();

        return view('partymeister-slides::backend.slide_clients.index', compact('paginator', 'grid'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $form = $this->form(SlideClientForm::class, [
            'method'  => 'POST',
            'route'   => 'backend.slide_clients.store',
            'enctype' => 'multipart/form-data'
        ]);

        return view('partymeister-slides::backend.slide_clients.create', compact('form'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(SlideClientRequest $request)
    {
        $form = $this->form(SlideClientForm::class);

        // It will automatically use current request, get the rules, and do the validation
        if ( ! $form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        SlideClientService::createWithForm($request, $form);

        flash()->success(trans('partymeister-slides::backend/slide_clients.created'));

        return redirect('backend/slide_clients');
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(SlideClient $record)
    {
        $form = $this->form(SlideClientForm::class, [
            'method'  => 'PATCH',
            'url'     => route('backend.slide_clients.update', [ $record->id ]),
            'enctype' => 'multipart/form-data',
            'model'   => $record
        ]);

        return view('partymeister-slides::backend.slide_clients.edit', compact('form'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(SlideClientRequest $request, SlideClient $record)
    {
        $form = $this->form(SlideClientForm::class);

        // It will automatically use current request, get the rules, and do the validation
        if ( ! $form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        SlideClientService::updateWithForm($record, $request, $form);

        flash()->success(trans('partymeister-slides::backend/slide_clients.updated'));

        return redirect('backend/slide_clients');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(SlideClient $record)
    {
        SlideClientService::delete($record);

        flash()->success(trans('partymeister-slides::backend/slide_clients.deleted'));

        return redirect('backend/slide_clients');
    }
}
