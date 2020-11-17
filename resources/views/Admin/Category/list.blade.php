@extends('Admin.app')
@section('title')
Category
@endsection
@section('style')
<link href="{{asset('assets/plugins/nestable/nestable.css')}}" rel="stylesheet" type="text/css" />
<style type="text/css">
	.color-box{
		height: 25px !important;
		width: 100px !important;
		border: 2px solid !important;
	}
	.dd-nodrag{
		pointer-events: none;
	}
	.dd-nodrag a{
		pointer-events: all;
	}
</style>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-5 col-8 align-self-center">
            <h3 class="text-themecolor m-b-0 m-t-0">Category</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Category</li>
            </ol>
        </div>
        <div class="col-md-7 col-4 align-self-center">
            <div class="d-flex m-t-10 justify-content-end">
                <div class="d-flex m-r-20 m-l-10 hidden-md-down">
        			<a href="{{route('admin.category.add')}}" class="btn btn-success">Add Category</a>                    
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
                                    <th>Title</th>
                                    <th>Image</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                    <!-- <th width="40%">Child</th> -->
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
<script src="{{asset('assets/plugins/nestable/jquery.nestable.js')}}"></script>
<script type="text/javascript">
	var table;
	$(document).ready(function() {
		$('[data-toggle="tooltip"]').tooltip();
	    table = $('#editable-datatable').dataTable({
	        processing: true,
	        serverSide: true,
	        ajax:{
	            headers: {'X-CSRF-Token': Laravel.csrfToken},
	            url:'{!! route("admin.category.data") !!}',
	            type:'POST'
	        },
	        columns: [
	            {data: 'id',name: 'id'},
	            {data: 'title',name: 'title'},
	            {data: 'image',name: 'image'},
	            {data: 'description',name: 'description'},
	            {data: 'action',name: 'action'},
	            
	            /*{data: 'child',name: 'child'}*/
	        ],
	        order: [[0, 'desc']],
	        "columnDefs": 
	        [
	        	{"targets": 2, "orderable": false},
	        	{"targets": 4, "orderable": false},
			    { "width": "10%", "targets": 0 }, 
		    ],
		    "drawCallback": function(settings) {
	        	$('.nestable').nestable({
					"maxDepth":100
				});
				console.log('asdasd');
				$('.nestable').nestable('collapseAll');
		    }
	    });
	});
	function delete_confirmation(ele,id){
		var error;
		Swal.fire({
			title: 'Are you sure you?',
			text: "All sub categories and child attached to this category will be deleted. You won't be able to revert this!",
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
				    url: '{{ route("admin.category.delete") }}',
				    dataType : 'json',
				    data: {id:id},
				    success: function(response) {
				    	if(response.succsess == true){
					        Swal.fire(
	        				  'Deleted!',
	        				  'Category has been deleted.',
	        				  'success'
	        				);
	        				reload_table();
				    	}
						else{
					        Swal.fire(
	        				  'Category could not delete!',
	        				  'Please try again later.',
	        				  'warning'
	        				);
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
		$('.nestable').nestable('destroy');
	    table.api().ajax.reload(); 
	}
</script>
@endsection