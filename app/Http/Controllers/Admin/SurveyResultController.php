<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use AidynMakhataev\LaravelSurveyJs\app\Models\SurveyResult;

use AidynMakhataev\LaravelSurveyJs\app\Models\Survey;
use App\Services\ESurvey\ESurveyFacade as ESurveyServe;

use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Pamu;
use App\Models\Unit;
use Gate;
use PDF;




class SurveyResultController extends Controller
{

    public function unitResult(Request $request, $id){

        abort_if(Gate::denies('survey_result_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $surveyResult = '';
        $surveyRating = [];
        $elements = ESurveyServe::surveyElements(Survey::find($id));
        $survey = Survey::findOrFail($id);
        
        // dd($survey->pamu);
        $surveyName = $survey->name;
        $action12 = 'http://127.0.0.1:8000/admin/esurvey/1/result/unit';
        
        $filters = [
            'unit'              => $request->unit?:'',
            'units'             => $units = $request->pamu?Unit::where('PAMU',$request->pamu)->get()->pluck('UnitCode'):Unit::where('PAMU',$survey->pamu)->get()->pluck('UnitCode'),
            'employee_types'    => ['Officer', 'Enlisted Personnel', 'CivHR', 'Enlisted Personnel & CivHR'],
            'employee_type'     => $request->employee_type?:'',
            'excludedQuestions' => $request->excludedQuestions??[],
            'pamus'             => Pamu::all()->pluck('pamu'),
            'pamu'              => $request->pamu??$survey->pamu,
            'questions'         => array_pluck($elements, 'name'),
        ];
        
        $options = [
            'sortSurveyMatrix',
            'sortQuestion',
        ];

        if($filters['unit']){
            $surveyResult = SurveyResult::whereRaw('LOWER(json) like ?','%"unit": "'.strtolower($filters['unit']).'"%');
         
                           
            if($request->employee_type=="Enlisted Personnel & CivHR"){
               
                $surveyResult = $surveyResult->whereRaw('LOWER(json) like ?','%"employee type": "enlisted personnel"%')->orWhereRaw('LOWER(json) like ?','%"employee type": "civhr"%' );
                
            }
            if($request->employee_type=="CivHR"){
                
                $surveyResult = $surveyResult->whereRaw('LOWER(json) like ?','%"employee type": "civhr"%' );
               
            }
            if($request->employee_type=="Enlisted Personnel"){
                
                $surveyResult = $surveyResult->whereRaw('LOWER(json) like ?','%"employee type": "enlisted personnel"%' );
              
            }
            if($request->employee_type=="Officer"){
                
                $surveyResult = $surveyResult->whereRaw('LOWER(json) like ?','%"employee type": "officer"%' );
             
            }
            
          
            
         
    
                                        
            $surveyResult = $surveyResult->where('survey_id', $id)->pluck('json')->toArray();
// dd($surveyResult);
            $surveyRating = ESurveyServe::getUnitResult($surveyResult, $id, $elements, $filters['excludedQuestions'], $options); //third parameter for sort option available options:  ['sortSurveyMatrix', 'filterMatrixBasedQuestion']
           
            // dd($surveyRating);
          
        }
       
        return view('admin.esurvey.result.showUnitOverAllSurveyResult',compact('id', 'filters', 'surveyRating','surveyName', 'action12'));
    }


    public function unitResultSearch(Request $request, $id){

        abort_if(Gate::denies('survey_result_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $surveyResult = '';
        $surveyRating = [];
        $elements = ESurveyServe::surveyElements(Survey::find($id));
        $survey = Survey::findOrFail($id);
        $surveyName = $survey->name;
        $action12 = 'http://127.0.0.1:8000/admin/esurvey/1/result/unit';
        
        $filters = [
            'unit'              => $request->unit?:'',
            'units'             => $units = $request->pamu?Unit::where('PAMU',$request->pamu)->get()->pluck('UnitCode'):Unit::where('PAMU',$survey->pamu)->get()->pluck('UnitCode'),
            'employee_types'    => ['Officer', 'Enlisted Personnel', 'CivHR', 'Enlisted Personnel & CivHR'],
            'employee_type'     => $request->employee_type?:'',
            'excludedQuestions' => $request->excludedQuestions??[],
            'pamus'             => Pamu::all()->pluck('pamu'),
            'pamu'              => $request->pamu??$survey->pamu,
            'questions'         => array_pluck($elements, 'name'),
        ];
        
        $options = [
            'sortSurveyMatrix',
        ];

        if($filters['unit']){
            $surveyResult = SurveyResult::whereRaw('LOWER(json) like ?','%"unit": "'.strtolower($filters['unit']).'"%');
         
                           
            if($request->employee_type=="Enlisted Personnel & CivHR"){
               
                $surveyResult = $surveyResult->whereRaw('LOWER(json) like ?','%"employee type": "enlisted personnel"%')->orWhereRaw('LOWER(json) like ?','%"employee type": "civhr"%' );
                
            }
            if($request->employee_type=="CivHR"){
                
                $surveyResult = $surveyResult->whereRaw('LOWER(json) like ?','%"employee type": "civhr"%' );
               
            }
            if($request->employee_type=="Enlisted Personnel"){
                
                $surveyResult = $surveyResult->whereRaw('LOWER(json) like ?','%"employee type": "enlisted personnel"%' );
              
            }
            if($request->employee_type=="Officer"){
                
                $surveyResult = $surveyResult->whereRaw('LOWER(json) like ?','%"employee type": "officer"%' );
             
            }
                                        
            $surveyResult = $surveyResult->where('survey_id', $id)->pluck('json')->toArray();

            $surveyRating = ESurveyServe::getUnitResult($surveyResult, $elements, $filters['excludedQuestions'], $options, $id); //third parameter for sort option available options:  ['sortSurveyMatrix', 'filterMatrixBasedQuestion']
           
          
        }

        return view('admin.esurvey.result.showUnitOverAllSurveyResultPDF',compact('id', 'filters', 'surveyRating','surveyName', 'action12'));
    }

    public function resultList(Request $request,$id){        
        abort_if(Gate::denies('survey_result_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $survey = Survey::findOrFail($id);
        $surveyName = $survey->name;
       
        if ($request->ajax()) {

            $query = $survey->results();
            //$resultJosn = $query->json;

            $table = Datatables::of($query);
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');
            $table->editColumn('actions', function ($row) {
                $viewGate = 'survey_result_show';
                $editGate = 'n/a';
                $deleteGate = 'n/a';
                $crudRoutePart = 'esurvey.result';

                return view('partials.datatablesActions', compact(
                'viewGate',
                'editGate',
                'deleteGate',
                'crudRoutePart',
                'row'
            ));
            });

           

            $table->addColumn('unit', function($row){
             
                $surveyRating = SurveyResult::findOrFail($row->id)->json;
                return $surveyRating['Unit']??'';
               
            });          
            
            $table->addColumn('pamu', function($row){
             
                $surveyRating = SurveyResult::findOrFail($row->id)->json;
                return $surveyRating['Pamu']??'';
               
            });   

            $table->rawColumns(['actions', 'placeholder', 'unit']);

            return $table->make(true);
        }

        return view('admin.esurvey.result',compact('id','surveyName'));
    }

    public function resultShow($id){

        abort_if(Gate::denies('survey_result_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $survey_id = SurveyResult::where("id", $id)->pluck("survey_id");
        // dd($survey_id);

        // $survey = Survey::get();
        // $count = count($survey[0]->json["pages"]);
        // $countainer = array();
        // for($x=0; $x<$count; $x++)
        // {          
        //     $countainer[] = $survey[0]->json["pages"][$x];
        // }

        // $count_elements = count($countainer);
        // $comtainer_elements = array();

        // for($x=0; $x<$count_elements; $x++)
        // {          
        //     $comtainer_elements[] = $countainer[$x]["elements"];
        // }

        // $count_items = count($comtainer_elements);
        // $comtainer_items = array();

        // for($x=0; $x<$count_items; $x++)
        // {          
         
        //     for($y=0; $y<count($comtainer_elements[$x]); $y++)
        //     {    
        //         $comtainer_items[] = $comtainer_elements[$x][$y]["name"]; 
        //     }    
        // }

        // // return $comtainer_items;

        // ///////////////////

        // $survey_result = SurveyResult::where("id", $id)->first();

        // // return $survey_result->json;

        // $surveyRating = array();
        // foreach($comtainer_items as $id => $value)
        // {

      
        //         if (array_key_exists($value, $survey_result->json))
        //         {

        //             $surveyRating[$value] = $survey_result->json["$value"];
        //         }            

            

    

        // }

        
        $surveyRating = ESurveyServe::getSurveyRating($id, ['sortSurveyMatrix', 'sortQuestions']); //available options:  ['sortSurveyMatrix', 'filterMatrixBasedQuestion']
        // $surveyRating = $surveyRating->reverse();
        // foreach($surveyRating as $key => $item){
        //     $test = $surveyRating
        // }

        
        return view('admin.esurvey.result.showIndividualSurveyResult',compact('surveyRating'));
    }

    public function resultShowPDF($id){

        abort_if(Gate::denies('survey_result_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = ESurveyServe::getSurveyRating($id, ['sortSurveyMatrix', 'sortQuestions']); //available options:  ['sortSurveyMatrix', 'filterMatrixBasedQuestion']
        // $surveyRating = $surveyRating->reverse();
        // dd($data);
        view()->share('surveyRating',$data);

        $pdf = PDF::loadView('admin.esurvey.result.showIndividualSurveyResultPDF', $data);
        
        // download PDF file with download method
        return $pdf->setPaper("a4")->stream('pdf_file.pdf');
        
       
    }

    public function unitResultPDF(Request $request, $id){

        abort_if(Gate::denies('survey_result_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $surveyResult = '';
        $surveyRating = [];
        $elements = ESurveyServe::surveyElements(Survey::find($id));
        $survey = Survey::findOrFail($id);
        $surveyName = $survey->name;
        
        $filters = [
            'unit'              => $request->unit?:'',
            'units'             => $units = $request->pamu?Unit::where('PAMU',$request->pamu)->get()->pluck('UnitCode'):Unit::where('PAMU',$survey->pamu)->get()->pluck('UnitCode'),
            'employee_types'    => ['Officer', 'Enlisted Personnel', 'CivHR'],
            'employee_type'     => $request->employee_type?:'',
            'excludedQuestions' => $request->excludedQuestions??[],
            'pamus'             => Pamu::all()->pluck('pamu'),
            'pamu'              => $request->pamu??$survey->pamu,
            'questions'         => array_pluck($elements, 'name'),
        ];
        
        $options = [
            'sortSurveyMatrix',
            'sortQuestion',
        ];

        if($filters['unit']){
            $surveyResult = SurveyResult::whereRaw('LOWER(json) like ?','%"unit": "'.strtolower($filters['unit']).'"%');
         
                           
            if($request->employee_type=="Enlisted Personnel & CivHR"){
               
                $surveyResult = $surveyResult->whereRaw('LOWER(json) like ?','%"employee type": "enlisted personnel"%')->orWhereRaw('LOWER(json) like ?','%"employee type": "civhr"%' );
                
            }
            if($request->employee_type=="CivHR"){
                
                $surveyResult = $surveyResult->whereRaw('LOWER(json) like ?','%"employee type": "civhr"%' );
               
            }
            if($request->employee_type=="Enlisted Personnel"){
                
                $surveyResult = $surveyResult->whereRaw('LOWER(json) like ?','%"employee type": "enlisted personnel"%' );
              
            }
            if($request->employee_type=="Officer"){
                
                $surveyResult = $surveyResult->whereRaw('LOWER(json) like ?','%"employee type": "officer"%' );
             
            }
                                        
            $surveyResult = $surveyResult->where('survey_id', $id)->pluck('json')->toArray();

            $data = ESurveyServe::getUnitResult($surveyResult, $elements, $filters['excludedQuestions'], $options); //third parameter for sort option available options:  ['sortSurveyMatrix', 'filterMatrixBasedQuestion']
           
        }
        
    //     // dd($data);

    //     // dd($data2['unit'][0]);
        view()->share('surveyRating2', $data);

        $bong = "testing";

        $pdf = PDF::loadView('admin.esurvey.result.UnitPDF', ["bong"=>$bong ]);
        return $pdf->setPaper("a4")->stream('pdf_file.pdf');
    }

}
