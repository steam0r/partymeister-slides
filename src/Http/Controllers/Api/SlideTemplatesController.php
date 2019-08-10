<?php

namespace Partymeister\Slides\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Motor\Backend\Http\Controllers\Controller;
use Partymeister\Slides\Http\Requests\Backend\SlideTemplateRequest;
use Partymeister\Slides\Models\SlideTemplate;
use Partymeister\Slides\Services\SlideTemplateService;
use Partymeister\Slides\Transformers\SlideTemplateTransformer;

/**
 * Class SlideTemplatesController
 * @package Partymeister\Slides\Http\Controllers\Api
 */
class SlideTemplatesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $paginator = SlideTemplateService::collection()->getPaginator();
        $resource  = $this->transformPaginator($paginator, SlideTemplateTransformer::class);

        return $this->respondWithJson('SlideTemplate collection read', $resource);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param SlideTemplateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(SlideTemplateRequest $request)
    {
        $result   = SlideTemplateService::create($request)->getResult();
        $resource = $this->transformItem($result, SlideTemplateTransformer::class);

        return $this->respondWithJson('SlideTemplate created', $resource);
    }


    /**
     * Display the specified resource.
     *
     * @param SlideTemplate $record
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(SlideTemplate $record)
    {
        $result   = SlideTemplateService::show($record)->getResult();
        $resource = $this->transformItem($result, SlideTemplateTransformer::class);

        return $this->respondWithJson('SlideTemplate read', $resource);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param SlideTemplateRequest $request
     * @param SlideTemplate        $record
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(SlideTemplateRequest $request, SlideTemplate $record)
    {
        $result   = SlideTemplateService::update($record, $request)->getResult();
        $resource = $this->transformItem($result, SlideTemplateTransformer::class);

        return $this->respondWithJson('SlideTemplate updated', $resource);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param SlideTemplate $record
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(SlideTemplate $record)
    {
        $result = SlideTemplateService::delete($record)->getResult();

        if ($result) {
            return $this->respondWithJson('SlideTemplate deleted', [ 'success' => true ]);
        }

        return $this->respondWithJson('SlideTemplate NOT deleted', [ 'success' => false ]);
    }
}