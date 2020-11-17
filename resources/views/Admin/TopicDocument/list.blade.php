@extends('Admin.app')
@section('title')
Topic Document
@endsection
@section('style')
<link href="{{asset('assets/plugins/nestable/nestable.css')}}" rel="stylesheet" type="text/css" />
<style type="text/css">
	.color-box{
		height: 25px !important;
		width: 100px !important;
		border: 2px solid !important;
	}
</style>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-5 col-8 align-self-center">
            <h3 class="text-themecolor m-b-0 m-t-0">Topic Document</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Topic Document</li>
            </ol>
        </div>
        <div class="col-md-7 col-4 align-self-center">
            <div class="d-flex m-t-10 justify-content-end">
                <div class="d-flex m-r-20 m-l-10 hidden-md-down">
        			<a href="{{route('admin.topic.document.add')}}" class="btn btn-success">Add Topic Document</a>
        		</div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table striped m-b-20" id="editable-datatable">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Topic</th>
                                    <th>Document Title</th>
                                    <th>Document Wrapper Image</th>
                                    <th>Document Type</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{asset('assets/plugins/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
<script src="{{asset('assets/plugins/datatables/media/js/dataTables.bootstrap.js')}}"></script>
<script src="{{asset('assets/plugins/tiny-editable/mindmup-editabletable.js')}}"></script>
<script type="text/javascript">
	var table;
	$(document).ready(function() {
		$('[data-toggle="tooltip"]').tooltip();
	    table = $('#editable-datatable').dataTable({
	        processing: true,
	        serverSide: true,
	        ajax:{
	            headers: {'X-CSRF-Token': Laravel.csrfToken},
	            url:'{!! route("admin.topic.document.data") !!}',
	            type:'POST'
	        },
	        columns: [
	            {data: 'id',name: 'id'},
	            {data: 'topic_id',name: 'topic_id'},
	            {data: 'title',name: 'title'},
	            {data: 'image',name: 'image'},
	            {data: 'is_paid',name: 'is_paid'},
	            {data: 'action',name: 'action'},
	        ],
	        order: [[0, 'desc']],
	        "columnDefs": 
	        [
	        	{"targets": 3, "orderable": false},
	        	{"targets": 5, "orderable": false},
			    { "width": "10%", "targets": 0 }, 
		    ],
	    });
	});
	function delete_confirmation(ele,id)
	{
		var error;
		Swal.fire({
			title: 'Are you sure?',
			text: "You won't be able to revert this!",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, delete it!'
		}).then((result) => {
			if (result.value) {
				$.ajax({
				    type: "POST",
				    headers: {'X-CSRF-Token': Laravel.csrfToken},
				    url: '{{ route("admin.topic.document.delete") }}',
				    dataType : 'json',
				    data: {id:id},
				    success: function(response) {
				    	if(response.succsess == true)
				    	{
					        Swal.fire(
	        				  'Deleted!',
	        				  'Topic Docuemnt has been deleted.',
	        				  'success'
	        				)
	        				reload_table();
				    	}
				    },
				    error: function (jqXHR, status, exception) {
				    	if (jqXHR.status === 0) {
			    	        error = 'Not connected.\nPlease verify your network connection.';
			    	    } else if (jqXHR.status == 404) {
			    	        error = 'The requested page not found. [404]';
			    	    } else if (jqXHR.status == 500) {
			    	        error = 'Internal Server Error [500].';
			    	    } else if (exception === 'parsererror') {
			    	        error = 'Requested JSON parse failed.';
			    	    } else if (exception === 'timeout') {
			    	        error = 'Time out error.';
			    	    } else if (exception === 'abort') {
			    	        error = 'Ajax request aborted.';
			    	    } else {
			    	        error = 'Uncaught Error.\n' + jqXHR.responseText;
			    	    }
			            Swal.fire('Error!',error,'error');
			        }
				});
				
			}
		})
	}
	function reload_table(){
	    table.api().ajax.reload(); 
	}
</script>
@endsection