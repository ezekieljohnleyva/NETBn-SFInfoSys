@extends('layouts.admin')
@section('content')

<div class="main-panel">
    <div class="col-lg-12">
        <div class="row">
            @foreach($respondents->chunk(4) as $chunk)
                @foreach($chunk as $respondents)
                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
                        <div class="card card-statistics">
                            <div class="card-body">
                                <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                                    <div class="float-left">
                                        <i class="fas fa-cube fa-5x text-danger"></i>
                                    </div> <div class="float-right">
                                        <p class="mb-0 text-right">{{ $respondents->name }}</p> 
                                        <div class="fluid-container">
                                            <h3 class="font-weight-medium text-right mb-0">{{ $respondents->pamu }}</h3>
                                        </div>
                                    </div>
                                </div> 
                                <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left"><i aria-hidden="true" class="fas fa-exclamation-circle"></i> 
                                {{ empty($respondents->survey_respondents)?'':$respondents->survey_respondents->count() }} Respondents </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>
        <div class="row">
            <div class="col-lg-12 grid-margin">
                <div class="card">
                    <div class="card-header">
                        {{ trans('Survey') }} {{ trans('global.list') }}
                    </div>
                    <div class="card-body">
                        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-SurveyList">
                            <thead>
                                <tr>
                                    <th width="50">No</th>
                                    <th>
                                        {{ trans('Survey List') }}
                                    </th>
                                    <th>
                                        {{ trans('Total Respondents') }}
                                    </th>
                                    <th>
                                        {{ trans('Date Created') }}
                                    </th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')

@parent

<script>

    $(function () {
  
        let dtOverrideGlobals = {
            processing: true,
            serverSide: true,
            aaSorting: [],
            ajax: "{{ route('admin.home') }}",
            columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'name', name: 'name'},
                    {data: 'survey_result_count', name: 'survey_result_count'},
                    {data: 'created_at', name: 'created_at'},
            ],
            orderCellsTop: true,
            pageLength: 25,
        };
        let table = $('.datatable-SurveyList').DataTable(dtOverrideGlobals);
    
    });

</script>
@endsection