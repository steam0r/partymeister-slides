<?php

namespace Partymeister\Slides\Http\Controllers\Api;

use Motor\Backend\Http\Controllers\Controller;

use Partymeister\Slides\Models\SlideTemplate;
use Partymeister\Slides\Http\Requests\Backend\SlideTemplateRequest;
use Partymeister\Slides\Services\SlideTemplateService;
use Partymeister\Slides\Transformers\SlideTemplateTransformer;

class SlideTemplatesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paginator = SlideTemplateService::collection()->getPaginator();
        $resource = $this->transformPaginator($paginator, SlideTemplateTransformer::class);

        return $this->respondWithJson('SlideTemplate collection read', $resource);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(SlideTemplateRequest $request)
    {
        $result = SlideTemplateService::create($request)->getResult();
        $resource = $this->transformItem($result, SlideTemplateTransformer::class);

        return $this->respondWithJson('SlideTemplate created', $resource);
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show(SlideTemplate $record)
    {
        $result = SlideTemplateService::show($record)->getResult();
        $resource = $this->transformItem($result, SlideTemplateTransformer::class);

        return $this->respondWithJson('SlideTemplate read', $resource);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(SlideTemplateRequest $request, SlideTemplate $record)
    {
        $result = SlideTemplateService::update($record, $request)->getResult();
        $resource = $this->transformItem($result, SlideTemplateTransformer::class);

        return $this->respondWithJson('SlideTemplate updated', $resource);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(SlideTemplate $record)
    {
        $result = SlideTemplateService::delete($record)->getResult();

        if ($result) {
            return $this->respondWithJson('SlideTemplate deleted', ['success' => true]);
        }
        return $this->respondWithJson('SlideTemplate NOT deleted', ['success' => false]);
    }
}