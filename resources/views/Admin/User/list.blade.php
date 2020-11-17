@extends('Admin.app')
@section('title')
Users
@endsection
@section('style')
<link href="{{asset('assets/plugins/bootstrap-switch/bootstrap-switch.min.css')}}" id="theme" rel="stylesheet">
<style type="text/css">
    #error_tag{
        color: red;
    }
    h4{
        margin: 0 !important;
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
            <h3 class="text-themecolor m-b-0 m-t-0">List Of Users</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Topic Video</li>
            </ol>
        </div>
    </div>
        <div class="row">
            <div class="col s12">
                <div class="card">
                    <div class="card-body">
                        @php
                        $user_type = Request::get('user-type');
                        @endphp
                        <select class="select2-multiple" title="Select Job Number!" style="width: 100%" id="user_type_filter" onchange="reload_table();">
                            <option value="">-select user type-</option>
                            <option {{($user_type == 'paid') ? 'selected' : ''}} value="1_">Paid User</option>
                            <option {{($user_type == 'free') ? 'selected' : ''}} value="0_">Free User</option>
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
                                    <th>User Type</th>
									<th>Phone Number</th>
                                    <th>Block / Unblock</th>
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
<script src="{{asset('assets/plugins/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
<script src="{{asset('assets/plugins/datatables/media/js/dataTables.bootstrap.js')}}"></script>
<script src="{{asset('assets/plugins/tiny-editable/mindmup-editabletable.js')}}"></script>
<script src="{{asset('assets/plugins/bootstrap-switch/bootstrap-switch.min.js')}}"></script>
<script type="text/javascript">
    var table;
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
        table = $('#editable-datatable').dataTable({
            processing: true,
            serverSide: true,
            ajax:{
                headers: {'X-CSRF-Token': Laravel.csrfToken},
                url:'{!! route("admin.user.data") !!}',
                type:'POST',
                data: function (d) {
                    d.user_type_filter = $('#user_type_filter').val();
                }
            },
            columns: [
                {data: 'id',name: 'id'},
                {data: 'f_name',name: 'f_name'},
                {data: 'gender',name: 'gender'},
                {data: 'is_paid',name: 'is_paid'},
                {data: 'phone',name: 'phone'},
                {data: 'is_block',name: 'is_block'},
                {data: 'action',name: 'action'}, 
            ],
            order: [[0, 'desc']],
            "columnDefs": 
            [
                {"targets": 5, "orderable": false},
                {"targets": 6, "orderable": false},
                { "width": "10%", "targets": 0 }, 
            ],
            "drawCallback": function(settings, json) {
                $(".bootstrap-switch, .bt-switch input[type='checkbox'], .bt-switch input[type='radio']").bootstrapSwitch();
            }
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
            url: '{{ route("admin.user.user_info") }}',
            dataType : 'json',
            data: {id:id},
            success: function(response) {
                if(response.succsess == true)
                {
                    Swal.fire({
                      title: "<i>"+check_null(response.user_data.f_name)+"</i>", 
                      html: "<b>Mobile</b> : "+check_null(response.user_data.phone)+"</br><b>Date of Birth</b> : "+check_null(response.user_data.dob)+"</br><b>Gender</b> : "+check_null(response.user_data.gender)+"</br><b>Email</b> : "+check_null(response.user_data.email)+"</br><b>Address</b> : "+check_null(response.user_data.address)+"</br><b>Education</b> : "+check_null(response.user_data.education)+"</br><b>Remark </b><form action='{{ route('admin.user.user_remark') }}' method='post'><textarea name='remark'>"+response.user_data.remark+"</textarea><input type='hidden' name='id' value='"+response.user_data.id+"'></br><input name='_token' type='hidden' value='{{ csrf_token() }}'><input class='btn btn-primary' type='submit'></form>",  
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
    function change_block(ele,id)
    {
        if($(ele).is(':checked')!=$(ele).attr('data-old')){
            var status;
            var status_txt;
            if($(ele).prop("checked") == true){
                status = 1;
                status_txt = 'Block';
            }
            else
            {
                status = 0;
                status_txt = 'Unblock';
            }
            Swal.fire({
                title: 'Are you sure to '+status_txt+' this user ?',
                text: capitalizeFirstLetter(status_txt)+' user !',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, '+capitalizeFirstLetter(status_txt)+' it!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "POST",
                        headers: {'X-CSRF-Token': Laravel.csrfToken},
                        url: '{{ route("admin.user.change_block_status") }}',
                        dataType : 'json',
                        data: {id:id,status:status},
                        success: function(response) {
                            if(response.succsess == true)
                            {
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
                else
                {
                    $(ele).bootstrapSwitch('toggleState');
                }
            });
        }
    }
    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }
</script>
@endsection