@extends('Admin.app')
@section('title')
Privacy Policy
@endsection
@section('content')
<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-5 col-8 align-self-center">
            <h3 class="text-themecolor m-b-0 m-t-0">Privacy Policy</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Privacy Policy</li>
            </ol>
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
                                    <th>Privacy Policy</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            	@foreach($PrivacyPolicy as $key => $data)
                            	<tr>
                            		<td>{{$data->id}}</td>
                                    <td>{{$data->title}}</td>
                            		<td>{!! $data->content !!}</td>
                            		<td><a href="{{route('admin.privacy_policy.edit',$data->id)}}" data-toggle="tooltip" data-original-title="Edit"><i class="fas fa-pencil-alt text-inverse m-r-10"></i></a></td>
                            	</tr>
                            	@endforeach
                            </tbody>
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
	        order: [[0, 'desc']],
	        "columnDefs": 
	        [
	        	{"targets": 3, "orderable": false},
			    { "width": "10%", "targets": 0 }, 
		    ],
	    });
	});
</script>
@endsection