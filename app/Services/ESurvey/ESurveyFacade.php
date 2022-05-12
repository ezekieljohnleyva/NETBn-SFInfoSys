<?php

namespace App\Services\ESurvey;

use \Illuminate\Support\Facades\Facade;

class ESurveyFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'App\Services\ESurvey\ESurveyService';
    }
}