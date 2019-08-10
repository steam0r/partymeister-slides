<?php

namespace Partymeister\Slides\Http\Requests\Backend;

use Motor\Backend\Http\Requests\Request;

/**
 * Class SlideRequest
 * @package Partymeister\Slides\Http\Requests\Backend
 */
class SlideRequest extends Request
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [

        ];
    }
}
