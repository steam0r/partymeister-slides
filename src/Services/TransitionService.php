<?php

namespace Partymeister\Slides\Services;

use Motor\Backend\Services\BaseService;
use Partymeister\Slides\Models\Transition;

/**
 * Class TransitionService
 * @package Partymeister\Slides\Services
 */
class TransitionService extends BaseService
{

    /**
     * @var string
     */
    protected $model = Transition::class;
}
