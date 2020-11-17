@extends('Admin.app')
@section('title')
Users Packages
@endsection
@section('style')
<link href="{{asset('assets/plugins/select2/dist/css/select2.min.css')}}" rel="stylesheet" type="text/css" />
<style type="text/css">
    #error_tag{
        color: red;
    }
    h4{
        margin: 0 !important;
    }
    .select2-selection__rendered {
        line-height: 33px !important;
    }
    .select2-container .select2-selection--single {
        height: 38px !important;
    }
    .select2-selection__arrow {
        height: 37px !important;
    }
</style>
@endsection
@section('content')
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-5 col-8 align-self-center">
            <h3 class="text-themecolor m-b-0 m-t-0">List Of Users Packages</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Users Packages</li>
            </ol>
        </div>
    </div>
        <div class="row">
            <div class="col col-md-6">
                <div class="card">
                    <div class="card-body" >
                        <select style="width: 100%" id="user_list" class="select2 m-b-10 form-control" title="Select Users!" onchange="reload_table();">
                            <option value="">-select user-</option>
                            @foreach($User as $value)
                                <option value="{{$value->id}}">{{$value->f_name}} - {{$value->phone}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col col-md-6">
                <div class="card">
                    <div class="card-body">
                        <select style="width: 100%" id="package_type" class="form-control" title="Select Users!" onchange="reload_table();">
                                <option value="">-select type-</option>
                                <option value="running">Running</option>
                                <option value="expired">Expired</option>
                        </select>
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
                                    <th>User Name</th>
                                    <th>Gender</th>
									<th>Phone Number</th>
                                    <th>Package</th>
                                    <th>Purchase Date</th>
                                    <th>Expire Date</th>
                                    <th>Status</th>
                                    <th>View</th>
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
<script src="{{asset('assets/plugins/select2/dist/js/select2.full.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/plugins/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
<script src="{{asset('assets/plugins/datatables/media/js/dataTables.bootstrap.js')}}"></script>
<script src="{{asset('assets/plugins/tiny-editable/mindmup-editabletable.js')}}"></script>
<script type="text/javascript">
    $("#user_list").select2({
        placeholder:"-select users-",
        allowClear: true,
    });
    var table;
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
        table = $('#editable-datatable').dataTable({
            processing: true,
            serverSide: true,
            ajax:{
                headers: {'X-CSRF-Token': Laravel.csrfToken},
                url:'{!! route("admin.user.package.data") !!}',
                type:'POST',
                data: function (d) {
                    d.user_list = $('#user_list').val();
                    d.package_type = $('#package_type').val();
                }
            },
            columns: [
                {data: 'id',name: 'id'},
                {data: 'f_name',name: 'f_name'},
                {data: 'gender',name: 'gender'},
                {data: 'phone',name: 'phone'},
                {data: 'package_title',name: 'package_title'},
                {data: 'created_at',name: 'created_at'},
                {data: 'expiry_date',name: 'expiry_date'},
                {data: 'status',name: 'status'},
                {data: 'action',name: 'action'},
            ],
            order: [[0, 'desc']],
            "columnDefs": 
            [
                {"targets": 5, "orderable": false},
                {"targets": 6, "orderable": false},
                { "width": "10%", "targets": 0 }, 
            ],
        });
    });
    function reload_table(){
        table.api().ajax.reload(); 
    }
    function check_null(val)
    {
        return (val === null || val == '') ? '-' : val;
    }
    function view_information(ele,id)
    {
        var error;
        $(ele).html("<i class='fa fa-spinner fa-spin'></i>");
        $.ajax({
            type: "POST",
            headers: {'X-CSRF-Token': Laravel.csrfToken},
            url: '{{ route("admin.user.package.package_info") }}',
            dataType : 'json',
            data: {id:id},
            success: function(response) {
                if(response.succsess == true)
                {
                    console.log(response.package.length);
                    Swal.fire({
                        title: "<i>"+check_null(response.response_data.user_name)+"</i>", 
                        html: "<b>Mobile</b> : "+check_null(response.response_data.phone)+"</br><b>Package</b> : <select>"+$.each(response.package,function(index)
                            {+"<option>"+
                                response.package[index].title;
                                +"</option>"+                                
                            })+"</select></br><b>Expire Date</b> : "+check_null(response.response_data.expiry_date)+"</br><b>Transaction Id</b> : "+check_null(response.response_data.transaction_id)+"</br><b>Order Id</b> : "+check_null(response.response_data.order_id)+"</br><b>Amount</b> : "+check_null(response.response_data.amount),  
                        confirmButtonText: "Close", 
                    });
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
    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }
</script>
@endsection
