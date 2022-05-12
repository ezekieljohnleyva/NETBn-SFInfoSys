@extends('layouts.admin')
@section('content')

@can('survey_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.esurvey.create') }}">
                {{ trans('Create') }} {{ trans('Survey') }}
            </a>
       
            <a class="btn btn-info" href="{{ route('admin.esurvey.generate-create') }}">
                {{ trans('Generate') }} {{ trans('Survey') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('Survey') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-AuditLog">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.auditLog.fields.id') }}
                    </th>
                    <th>
                        {{ trans('Name') }}
                    </th>
                    <th>
                        {{ trans('Pamu') }}
                    </th>
                    <th>
                        {{ trans('Task Status') }}
                    </th>
                    <th>
                        {{ trans('Created At') }}
                    </th>
                    <th>
                        {{ trans('Created By') }}
                    </th>
                    <th>
                        {{ trans('Updated By') }}
                    </th>
                    <th>
                        {{ trans('Team') }}
                    </th>
                    <th>
                        &nbsp;
                    </th>
                </tr>
            </thead>
        </table>
    </div>
</div>


@endsection
@section('scripts')
@parent
<script>
    $(function () {
    let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
    
    let dtOverrideGlobals = {
        buttons: dtButtons,
        processing: true,
        serverSide: true,
        retrieve: true,
        aaSorting: [],
        ajax: "{{ route('admin.esurvey.index') }}",
        columns: [
            { data: 'placeholder', name: 'placeholder' },
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'pamu', name: 'pamu' },
            { data: 'taskStatus', name: 'taskStatus' },
            { data: 'created_at', name: 'created_at' },
            { data: 'createdBy', name: 'createdBy' },
            { data: 'updatedBy', name: 'updatedBy' },
            { data: 'team', name: 'team' },
            { data: 'actions', name: '{{ trans('global.actions') }}' }
        ],
        orderCellsTop: true,
        order: [[ 1, 'desc' ]],
        pageLength: 100,
    };
    let table = $('.datatable-AuditLog').DataTable(dtOverrideGlobals);
    $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
  
});

</script>
@endsection