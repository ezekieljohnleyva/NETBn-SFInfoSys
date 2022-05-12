<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;

use Illuminate\Http\Request;
use DataTables;

use App\Models\Pamu;
use AidynMakhataev\LaravelSurveyJs\app\Models\Survey;
use AidynMakhataev\LaravelSurveyJs\app\Models\SurveyResult;
use App\Services\ESurvey\ESurveyFacade as ESurveyServe;
use App\Models\Survey as ESurvey;

class HomeController
{
    public function index(Request $request)
    {

        $user = 
        $respondents = ESurvey::filterIfNotAdmin()->with('survey_respondents')->where('task_status_id','1')->latest()->take(4)->get();
        if ($request->ajax()) {
            
            $listResult = ESurvey::filterIfNotAdmin()->withCount('survey_result')->orderBy('survey_result_count','DESC')->where('task_status_id','1')->get();
            return Datatables::of($listResult)
                    ->addIndexColumn()
                    ->editColumn('created_at', function($data)
                        { 
                            $formatedDate = Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)
                                            ->format('d F Y'); return $formatedDate; 
                        })
                    ->make(true);
        }
        return view('home', compact('respondents'));
    }
}
