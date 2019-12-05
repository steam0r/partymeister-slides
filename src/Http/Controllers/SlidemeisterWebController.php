<?php

namespace Partymeister\Slides\Http\Controllers;

use App\Http\Controllers\Controller;
use Partymeister\Slides\Models\SlideClient;

/**
 * Class SlidemeisterWebController
 * @package Partymeister\Slides\Http\Controllers
 */
class SlidemeisterWebController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \ReflectionException
     */
    public function index(SlideClient $record)
    {
        return view('partymeister-slides::slidemeister-web/index');
    }
}
