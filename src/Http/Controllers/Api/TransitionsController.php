<?php

namespace Partymeister\Slides\Http\Controllers\Api;

use Motor\Backend\Http\Controllers\Controller;

use Partymeister\Slides\Models\Transition;
use Partymeister\Slides\Http\Requests\Backend\TransitionRequest;
use Partymeister\Slides\Services\TransitionService;
use Partymeister\Slides\Transformers\TransitionTransformer;

class TransitionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paginator = TransitionService::collection()->getPaginator();
        $resource = $this->transformPaginator($paginator, TransitionTransformer::class);

        return $this->respondWithJson('Transition collection read', $resource);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(TransitionRequest $request)
    {
        $result = TransitionService::create($request)->getResult();
        $resource = $this->transformItem($result, TransitionTransformer::class);

        return $this->respondWithJson('Transition created', $resource);
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Transition $record)
    {
        $result = TransitionService::show($record)->getResult();
        $resource = $this->transformItem($result, TransitionTransformer::class);

        return $this->respondWithJson('Transition read', $resource);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(TransitionRequest $request, Transition $record)
    {
        $result = TransitionService::update($record, $request)->getResult();
        $resource = $this->transformItem($result, TransitionTransformer::class);

        return $this->respondWithJson('Transition updated', $resource);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transition $record)
    {
        $result = TransitionService::delete($record)->getResult();

        if ($result) {
            return $this->respondWithJson('Transition deleted', ['success' => true]);
        }
        return $this->respondWithJson('Transition NOT deleted', ['success' => false]);
    }
}