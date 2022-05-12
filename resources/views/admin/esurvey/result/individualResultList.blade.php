
    <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-AuditLog">
        <thead>
            <tr>
                <th width="10">

                </th>
                <th>
                    {{ trans('Survey No') }}
                </th>
                <th>
                    {{trans('Unit')}}
                </th>
                <th>
                    {{trans('Pamu')}}
                </th>
                <th>
                    Actions
                </th>
            </tr>
        </thead>
    </table>

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
    ajax: {
        "url": "{{ route('admin.esurvey.result.list',$id) }}",
        "type": "GET",
        // "data":function(data) {
        //     data.id = 1
        // },
    },
    columns: [
        { data: 'placeholder', name: 'placeholder' },
        { data: 'id', name: 'id' },
        { data: 'unit', name: 'json' },
        { data: 'pamu', name: 'json' },
        { data: 'actions', name: '{{ trans('global.actions') }}'  }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 10,
  };
  let table = $('.datatable-AuditLog').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
  
});

</script>

@endsection