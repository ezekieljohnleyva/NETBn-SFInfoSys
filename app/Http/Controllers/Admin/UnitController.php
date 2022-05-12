<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pamu;
use App\Models\Unit;
use App\Models\Survey as ESurvey;

class UnitController extends Controller
{
    public function unitList(Request $request){
        $prevRouteSurveyVal =  explode('/',url()->previous());

        $slugOrSurveyId = $prevRouteSurveyVal[count($prevRouteSurveyVal)-1];

        

       
        $unit = Unit::orderBy('UnitCode');
        
        if($request->pamu)
            $unit = $unit->where('PAMU',$request->pamu);

        else{
            if(is_numeric($slugOrSurveyId))
                $survey = ESurvey::find($slugOrSurveyId);
            else
                $survey = ESurvey::where('slug', $slugOrSurveyId)->first();

            if($survey->pamu)
                $unit->where('PAMU',$survey->pamu);
        }

   

        return $unit->get('UnitCode')->toArray();
    }
}
