<?php

namespace Partymeister\Slides\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Motor\Backend\Http\Controllers\Controller;
use Partymeister\Slides\Http\Requests\Backend\TransitionRequest;
use Partymeister\Slides\Models\Transition;
use Partymeister\Slides\Services\TransitionService;
use Partymeister\Slides\Transformers\TransitionTransformer;

/**
 * Class TransitionsController
 * @package Partymeister\Slides\Http\Controllers\Api
 */
class TransitionsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $paginator = TransitionService::collection()->getPaginator();
        $resource  = $this->transformPaginator($paginator, TransitionTransformer::class);

        return $this->respondWithJson('Transition collection read', $resource);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param TransitionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(TransitionRequest $request)
    {
        $result   = TransitionService::create($request)->getResult();
        $resource = $this->transformItem($result, TransitionTransformer::class);

        return $this->respondWithJson('Transition created', $resource);
    }


    /**
     * Display the specified resource.
     *
     * @param Transition $record
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Transition $record)
    {
        $result   = TransitionService::show($record)->getResult();
        $resource = $this->transformItem($result, TransitionTransformer::class);

        return $this->respondWithJson('Transition read', $resource);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param TransitionRequest $request
     * @param Transition        $record
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(TransitionRequest $request, Transition $record)
    {
        $result   = TransitionService::update($record, $request)->getResult();
        $resource = $this->transformItem($result, TransitionTransformer::class);

        return $this->respondWithJson('Transition updated', $resource);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param Transition $record
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Transition $record)
    {
        $result = TransitionService::delete($record)->getResult();

        if ($result) {
            return $this->respondWithJson('Transition deleted', [ 'success' => true ]);
        }

        return $this->respondWithJson('Transition NOT deleted', [ 'success' => false ]);
    }
}