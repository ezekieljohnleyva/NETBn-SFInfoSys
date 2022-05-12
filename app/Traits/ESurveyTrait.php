<?php

namespace App\Traits;

use Symfony\Component\HttpFoundation\Response;
use Gate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use DB;
use Carbon\Carbon;


trait ESurveyTrait
{
    public function scopeFilterIfNotAdmin($result, $table='')
    {
        if(!auth()->user()->roles->pluck('title')->contains('Admin'))
            return $result->where($table.'team_id',auth()->user()->team_id);
            
        return $result;
    }
   
}