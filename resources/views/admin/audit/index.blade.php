@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('cruds.auditLog.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-AuditLog">
            <thead>
                <tr>
                    <th width="10">

                    </th> 
                    <th>
                        User
                    </th>
                    <th>
                        Event
                    </th>
                    <th>
                        Model
                    </th>
                    <th>
                        IP Address
                    </th>
                    <th>
                        Created At
                    </th>
                    <th>
                        Action
                    </th>
                    <!-- <th>
                        &nbsp;
                    </th> -->
                </tr>
            </thead>
        </table>
    </div>
</div> 

<div id="viewModal" class="modal fade bd-example-modal-lg" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"></h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<span id="form_result2"></span>
				<form method="post" id="sample_form" class="form-horizontal">
					@csrf
					<div class="form-group">
						<label class="control-label">Id:</label>
						
                       
                        <span class="control-label" id="val1"></span>
						
					</div>
                    <div class="form-group">
						<label class="control-label">Edited By:</label>
						
                       
                        <span class="control-label" id="val2"></span>
						
					</div>
					<div class="form-group">
						<label class="control-label">Old Value</label>
						<span class="control-label" id="val3"></span>
					</div>
                    <div class="form-group">
						<label class="control-label">New Value</label>
						<span class="control-label" id="val4"></span>
					</div>

					<br />
					<div class="form-group" align="center">
						
						<input type="hidden" name="hidden_id" id="hidden_id" />
						</div>
				</form>
			</div>
		</div>
	</div>
</div>


@endsection
@section('scripts')
@parent
<script>
   $(document).ready(function(){

  let id;
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
  
  let dtOverrideGlobals = {
    buttons: dtButtons,
    processing: true,
    serverSide: true,
    retrieve: true,
    aaSorting: [],
    ajax: "{{ route('admin.audit-logs.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder', "defaultContent": ""},
      
{ data: 'creator.name', name: 'User', "defaultContent": ""},
{ data: 'event', name: 'Event' },
{ data: 'auditable_type', name: 'auditable_type' },
{ data: 'ip_address', name: 'ip_address' },
{ data: 'created_at', name: 'created_at' },
{
                data: 'action', 
                name: 'action', 
                orderable: true, 
                searchable: true
            },
// { data: 'actions', name: '{{ trans('global.actions') }}' }
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

  $(document).on('click', '.view', function(){
		//alert("sdfgds");
		id = $(this).attr('id');
		
		$('#form_result2').html();
		$.ajax({
			
			url :"/admin/audit-logs/"+id+"/show",
			dataType:"json",
			success: function(data)
			{
				
				$('#val1').text(data.result.id);
				$('#val2').text(data.result.user_id);
                $('#val3').text(data.result.old_values);
				$('#val4').text(data.result.new_values);
				$('#hidden_id').val(id);
				$('.modal-title').text('View Audit Logs');
				$('#viewModal').modal('show');
			},
			error: function (data) {
				console.log(data);
				alert("AYAW PA");
			}
		})
	});
  
});

</script>
<style>


/* Important part */
.modal-dialog{
    overflow-y: initial !important
}
.modal-body{
    height: 80vh;
    overflow-y: auto;
}
</style>

@endsection