@extends('Admin.app')
@section('title')
Wallet Withdraw Request
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
            <h3 class="text-themecolor m-b-0 m-t-0">List Of Wallet Withdraw Request</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Wallet Withdraw Request</li>
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
                                    <th>User Name</th>
                                    <th>User Mobile</th>
                                    <th>Gpay Mobile</th>
                                    <th>Amount</th>
                                    <th>Approve / Disapprove</th>
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
                url:'{!! route("admin.wallet.data") !!}',
                type:'POST',
            },
            columns: [
                {data: 'id',name: 'id'},
                {data: 'u_name',name: 'u_name'},
                {data: 'u_phone',name: 'u_phone'},
                {data: 'gpay_mobile_no',name: 'gpay_mobile_no'},
                {data: 'amount',name: 'amount'},
                {data: 'is_approve',name: 'is_approve'},
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
    function change_approve(ele,id)
    {
        if($(ele).is(':checked') != $(ele).attr('data-old')){
            var status;
            var status_txt;
            if($(ele).prop("checked") == true){
                status = 1;
                status_txt = 'Approve';
            }
            else
            {
                status = 0;
                status_txt = 'Disapprove';
            }
            Swal.fire({
                title: 'Are you sure to '+status_txt+' this user request?',
                text: capitalizeFirstLetter(status_txt)+' user request !',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, '+capitalizeFirstLetter(status_txt)+' it!'
            }).then((result) => {
                if (result.value) {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    })
                    $.ajax({
                        type: "POST",
                        headers: {'X-CSRF-Token': Laravel.csrfToken},
                        url: '{{ route("admin.wallet.change_approve_status") }}',
                        dataType : 'json',
                        data: {id:id,status:status},
                        success: function(response) {
                            if(response.succsess == true)
                            {
                                Toast.fire({
                                    type: 'success',
                                    title: response.message
                                })
                                reload_table();
                            }
                            else
                            {
                                Toast.fire({
                                    type: 'error',
                                    title: response.message
                                })
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
