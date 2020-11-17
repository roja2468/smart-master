@extends('Admin.app')
@section('title')
Contact Message
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
                <h3 class="text-themecolor">Contact Message</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                    <li class="breadcrumb-item active">Contact Message</li>
                </ol>
            </div>
            <div class="col-md-4 col-8 align-self-center">
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table striped m-b-20" id="editable-datatable">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Email</th>
                                        <th>Subject</th>
                                        <th>Message</th>
                                        <th>Date / Time</th>
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
                url:'{!! route("admin.contact.data") !!}',
                type:'POST'
            },
            columns: [
                {data: 'id',name: 'id'},
                {data: 'email',name: 'email'},
                {data: 'subject',name: 'subject'},
                {data: 'message',name: 'message'},
                {data: 'created_at',name: 'created_at'},
                {data: 'action',name: 'action'},
            ],
            order: [[0, 'desc']],
            "columnDefs": 
            [
                {"targets": 5, "orderable": false},
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
    function view_information(ele,id)
    {
        var error;
        $(ele).html("<i class='fa fa-spinner fa-spin'></i>");
        $.ajax({
            type: "POST",
            headers: {'X-CSRF-Token': Laravel.csrfToken},
            url: '{{ route("admin.contact.contact_info") }}',
            dataType : 'json',
            data: {id:id},
            success: function(response) {
                if(response.succsess == true)
                {
                    Swal.fire({
                      title: "<i>Contact Info.</i>", 
                      html: "<b>Name</b> : "+response.contact_data.name+"</br><b>Email</b> : "+response.contact_data.email+"</br><b>Subject</b> : "+response.contact_data.subject+"</br><b>Message</b> : "+response.contact_data.message+"</br><b>Created At</b> : "+response.date_create,  
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