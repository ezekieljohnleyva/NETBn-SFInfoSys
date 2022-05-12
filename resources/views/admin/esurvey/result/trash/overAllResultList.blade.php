@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        <ul class="nav nav-tabs">
            <li class="nav-item">
              <a class="nav-link {{request()->is("admin/esurvey/$id/result/list")?'active':'' }}" href="{{ route('admin.esurvey.result.list',$id) }}">Individual Survey Result</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{request()->is("admin/esurvey/$id/overAllResult/list")?'active':'' }}" href="#tabs-2">Overall Survey Result</a>
            </li>    
        </ul>
    </div>

    <div class="card-body">
        <div id="tabs-2" >
            {{-- {{dd($generatedPerSurveyUnitOverAllRatings)}} --}}
            <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-AuditLog">
                <thead>
                    <tr>
                        <th width="10">
        
                        </th>
                        <th>
                            {{trans('Unit')}}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($unitList as $key => $value )
                        <tr>
                            <td></td>
                            <td>{{$value}}</td>
                            <td>
                                @can('survey_result_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.esurvey.overAllResult.show',[$id, $value]) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection