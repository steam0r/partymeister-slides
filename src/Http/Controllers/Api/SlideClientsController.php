<?php

namespace Partymeister\Slides\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Motor\Backend\Http\Controllers\Controller;
use Partymeister\Slides\Http\Requests\Backend\SlideClientRequest;
use Partymeister\Slides\Models\SlideClient;
use Partymeister\Slides\Services\SlideClientService;
use Partymeister\Slides\Transformers\SlideClientTransformer;

/**
 * Class SlideClientsController
 * @package Partymeister\Slides\Http\Controllers\Api
 */
class SlideClientsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $paginator = SlideClientService::collection()->getPaginator();
        $resource  = $this->transformPaginator($paginator, SlideClientTransformer::class);

        return $this->respondWithJson('SlideClient collection read', $resource);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param SlideClientRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(SlideClientRequest $request)
    {
        $result   = SlideClientService::create($request)->getResult();
        $resource = $this->transformItem($result, SlideClientTransformer::class);

        return $this->respondWithJson('SlideClient created', $resource);
    }


    /**
     * Display the specified resource.
     *
     * @param SlideClient $record
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(SlideClient $record)
    {
        $result   = SlideClientService::show($record)->getResult();
        $resource = $this->transformItem($result, SlideClientTransformer::class);

        return $this->respondWithJson('SlideClient read', $resource);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param SlideClientRequest $request
     * @param SlideClient        $record
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(SlideClientRequest $request, SlideClient $record)
    {
        $result   = SlideClientService::update($record, $request)->getResult();
        $resource = $this->transformItem($result, SlideClientTransformer::class);

        return $this->respondWithJson('SlideClient updated', $resource);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param SlideClient $record
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(SlideClient $record)
    {
        $result = SlideClientService::delete($record)->getResult();

        if ($result) {
            return $this->respondWithJson('SlideClient deleted', [ 'success' => true ]);
        }

        return $this->respondWithJson('SlideClient NOT deleted', [ 'success' => false ]);
    }
}