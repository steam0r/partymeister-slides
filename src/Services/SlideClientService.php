<?php

namespace Partymeister\Slides\Services;

use Motor\Backend\Services\BaseService;
use Partymeister\Slides\Models\SlideClient;

/**
 * Class SlideClientService
 * @package Partymeister\Slides\Services
 */
class SlideClientService extends BaseService
{

    /**
     * @var string
     */
    protected $model = SlideClient::class;
}
