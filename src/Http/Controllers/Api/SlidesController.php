<?php

namespace Partymeister\Slides\Http\Controllers\Api;

use Motor\Backend\Http\Controllers\Controller;

use Partymeister\Slides\Models\Slide;
use Partymeister\Slides\Http\Requests\Backend\SlideRequest;
use Partymeister\Slides\Services\SlideService;
use Partymeister\Slides\Transformers\SlideTransformer;

class SlidesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paginator = SlideService::collection()->getPaginator();
        $resource = $this->transformPaginator($paginator, SlideTransformer::class);

        return $this->respondWithJson('Slide collection read', $resource);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(SlideRequest $request)
    {
        $result = SlideService::create($request)->getResult();
        $resource = $this->transformItem($result, SlideTransformer::class);

        return $this->respondWithJson('Slide created', $resource);
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Slide $record)
    {
        $result = SlideService::show($record)->getResult();
        $resource = $this->transformItem($result, SlideTransformer::class);

        return $this->respondWithJson('Slide read', $resource);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(SlideRequest $request, Slide $record)
    {
        $result = SlideService::update($record, $request)->getResult();
        $resource = $this->transformItem($result, SlideTransformer::class);

        return $this->respondWithJson('Slide updated', $resource);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Slide $record)
    {
        $result = SlideService::delete($record)->getResult();

        if ($result) {
            return $this->respondWithJson('Slide deleted', ['success' => true]);
        }
        return $this->respondWithJson('Slide NOT deleted', ['success' => false]);
    }
}