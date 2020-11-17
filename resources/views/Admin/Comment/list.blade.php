@extends('Admin.app')
@section('title')
Comments
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
            <div class="col-md-8 col-8 align-self-center">
                <h3 class="text-themecolor">Comments</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                    <li class="breadcrumb-item active">Comments</li>
                </ol>
            </div>
            <div class="col-md-4 col-8 align-self-center">
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Recent Comments</h4>
                        <div class="table-responsive">
                            <table class="table striped m-b-20" id="editable-datatable">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>User Name</th>
                                        <th>Comment For Type</th>
                                        <th>Comment For</th>
                                        <th>Comment</th>
                                        <th>Date / Time</th>
                                        <th>Approve / Unapprove</th>
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
    <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h4 class="modal-title" id="myModalLabel">Modal Heading</h4>
                    <button type="button" class="close ml-auto" data-dismiss="modal"
                        aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <h6>Text in a modal</h6>
                    <p>Duis mollis, est non commodo luctus, nisi erat porttitor ligula.</p>
                    <hr>
                    <h6>Overflowing text to show scroll behavior</h6>
                    <p>Cras mattis consectetur purus sit amet fermentum. Cras justo odio,
                        dapibus ac facilisis in, egestas eget quam. Morbi leo risus, porta
                        ac consectetur ac, vestibulum at eros.</p>
                    <p>Praesent commodo cursus magna, vel scelerisque nisl consectetur et.
                        Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor
                        auctor.</p>
                    <p>Aenean lacinia bibendum nulla sed consectetur. Praesent commodo
                        cursus magna, vel scelerisque nisl consectetur et. Donec sed odio
                        dui. Donec ullamcorper nulla non metus auctor fringilla.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
@endsection
@section('script')
<script src="{{asset('assets/plugins/bootstrap-switch/bootstrap-switch.min.js')}}"></script>
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
                url:'{!! route("admin.comment.data") !!}',
                type:'POST'
            },
            columns: [
                {data: 'id',name: 'id'},
                {data: 'user_id',name: 'user_id'},
                {data: 'type',name: 'type'},
                {data: 'comment_for_id',name: 'comment_for_id'},
                {data: 'comment',name: 'comment'},
                {data: 'created_at',name: 'created_at'},
                {data: 'is_approve',name: 'is_approve'},
                {data: 'action',name: 'action'},
            ],
            order: [[0, 'desc']],
            "columnDefs": 
            [
                {"targets": 6, "orderable": false},
                {"targets": 7, "orderable": false},
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
    function change_approve(ele,id)
    {
        var status;
        if($(ele).prop("checked") == true){
            status = 1;
        }
        else
        {
            status = 0;
        }
        $.ajax({
            type: "POST",
            headers: {'X-CSRF-Token': Laravel.csrfToken},
            url: '{{ route("admin.comment.change_approve_status") }}',
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
    function view_information(ele,id)
    {
        var error;
        $(ele).html("<i class='fa fa-spinner fa-spin'></i>");
        $.ajax({
            type: "POST",
            headers: {'X-CSRF-Token': Laravel.csrfToken},
            url: '{{ route("admin.comment.comment_info") }}',
            dataType : 'json',
            data: {id:id},
            success: function(response) {
                if(response.succsess == true)
                {
                    Swal.fire({
                      title: "<i>Comment Info.</i>", 
                      html: "<b>Comment User</b> : "+response.comment_data.user_name+"</br><b>Comment For Type</b> : "+response.comment_data.comment_for_type+"</br><b>Comment Status</b> : "+response.comment_data.approve+"</br><b>Comment For</b> : "+response.comment_data.comment_for+"</br><b>Created At</b> : "+response.comment_data.created_at,  
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
</script>
@endsection