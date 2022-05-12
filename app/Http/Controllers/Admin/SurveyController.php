<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use AidynMakhataev\LaravelSurveyJs\app\Models\Survey;
use AidynMakhataev\LaravelSurveyJs\app\Models\SurveyResult;


use App\Models\Survey as ESurvey;
use App\Models\TaskStatus;
use App\Models\Pamu;

use App\Http\Requests\StoreSurveyRequest as StoreESurveyRequest;
use App\Http\Requests\UpdateSurveyRequest as UpdateESurveyRequest;

use AidynMakhataev\LaravelSurveyJs\app\Http\Resources\SurveyResource;
use AidynMakhataev\LaravelSurveyJs\app\Http\Requests\CreateSurveyRequest;
use AidynMakhataev\LaravelSurveyJs\app\Http\Requests\UpdateSurveyRequest;

use AidynMakhataev\LaravelSurveyJs\app\Http\Resources\SurveyResultResource;

use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Gate;

use File;

class SurveyController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) 
        {
            $query = ESurvey::query()->filterIfNotAdmin()->select(sprintf('%s.*', (new ESurvey())->table));
            $table = Datatables::of($query);
          
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'survey_show';
                $editGate = 'survey_edit';
                $deleteGate = 'survey_delete';
                $crudRoutePart = 'esurvey';

                return view('partials.datatablesActions', compact(
                'viewGate',
                'editGate',
                'deleteGate',
                'crudRoutePart',
                'row'
            ));
            });

            $table->addColumn('team', function($row){
                return $row->team->name??'';
            });

            $table->addColumn('updatedBy', function($row){
                return $row->updatedBy->name??'';
            });

            $table->addColumn('createdBy', function($row){
                return $row->createdBy->name??'';
            });

            $table->addColumn('taskStatus', function($row){
                return $row->task_status->name??'';
            });

            $table->rawColumns(['actions', 'placeholder', 'team', 'createdBy', 'updatedBy', 'taskStatus']);

            return $table->make(true);
        }

        return view('admin.esurvey.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('survey_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $statuses = TaskStatus::all()->pluck('name', 'id');
        $pamus = Pamu::all()->pluck('pamu', 'pamu')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.esurvey.create',compact('statuses', 'pamus'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreESurveyRequest $request)
    {
        $data             = $request->all();
        $data['slug']     = str_replace(' ', '-', strtolower($request->name));
        $data['json']     = '{
                                "pages": []
                            }';

       $survey             = ESurvey::create($data);
       $survey->created_by = auth()->user()->id;
       $survey->updated_by = auth()->user()->id;
       $survey->team_id    = auth()->user()->team_id;
       $survey->save();

        return redirect()->route('admin.esurvey.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)


    {
        $address = env('APP_URL');
        return redirect($address.'admin/survey/'.$id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        abort_if(Gate::denies('survey_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $survey = ESurvey::findOrFail($id);
        $survey->load('createdBy');

        $statuses = TaskStatus::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $pamus = Pamu::all()->pluck('pamu', 'pamu')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.esurvey.edit', compact('survey','statuses', 'pamus'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateESurveyRequest $request, $id)
    {
        $survey = ESurvey::findOrFail($id);
        $survey->update($request->all());
        $survey->slug = str_replace(' ', '-', strtolower($request->name));
        $survey->save();

        return redirect()->route('admin.esurvey.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        abort_if(Gate::denies('survey_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $survey = Survey::find($id);

        if (is_null($survey)) {
            return response()->json('Survey not found', 404);
        }
        $survey->delete();

        return back();

    }

    public function surveyLaunch($id){
        // abort_if(Gate::denies('survey_launch'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        abort_if(Gate::denies('survey_launch'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $address = env('APP_URL');
        // dd($address);
        $survey = Survey::findOrFail($id); 
        
        return redirect($address.'survey/'.$survey->slug);
        
    }

    public function surveyDuplicate($id){
        abort_if(Gate::denies('survey_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $survey = Survey::findOrFail($id); 

        $duplicateSurvey = $survey->replicate();
        $duplicateSurvey->name = $duplicateSurvey->name.'_Copy';
        $duplicateSurvey->slug = $duplicateSurvey->slug.'_Copy';
        $duplicateSurvey->save();

        return redirect()->route('admin.esurvey.index');
    }


    public function createGenerate()
    {
        abort_if(Gate::denies('survey_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $statuses = TaskStatus::all()->pluck('name', 'id');
        $pamus = Pamu::all()->pluck('pamu', 'pamu')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.esurvey.generate-create',compact('statuses', 'pamus'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeGenerate(StoreESurveyRequest $request)
    {
        // $surveyData       = $request->
        $data             = $request->all();
        $data['slug']     = str_replace(' ', '-', strtolower($request->name));
        $data['json']     = File::get(public_path('storage/data/otig31.json'));
        // $data['json']     = json_decode($json);
        // return $json;
       $survey             = ESurvey::create($data);
       $survey->created_by = auth()->user()->id;
       $survey->updated_by = auth()->user()->id;
       $survey->team_id    = auth()->user()->team_id;
       $survey->save();

        return redirect()->route('admin.esurvey.index');
    }
   
}

