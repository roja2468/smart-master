@extends('Admin.app')
@section('title')
App Notification
@endsection
@section('style')
<link rel="stylesheet" type="text/css" href="{{asset('assets/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css')}}">
@endsection
@section('content')
<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-8 align-self-center">
            <h3 class="text-themecolor m-b-0 m-t-0">Edit App Notification</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                <li class="breadcrumb-item active">Edit App Notification</li>
            </ol>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form class="m-t-40" method="post" action="{{route('admin.app_notification.update',$notification->id)}}" id="edit_app_notification" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group m-b-40">
                            <label for="title">App Notification Title</label>
                            <input id="title" class="form-control" name="title" data-toggle="tooltip" data-placement="bottom" value="{{old('title',$notification->title)}}" title="Enter Title!">
                            @error('title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('title') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group m-b-40">
                            <label for="content">App Notification</label>
                            <textarea id="content" class="form-control" name="content" data-toggle="tooltip" data-placement="bottom" title="Enter Content!">{!! $notification->content !!}</textarea>
                            @error('content')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('content') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group m-b-40">
                            <label for="notification_date">Notification Date</label>
                            <input id="notification_date" class="form-control singledate" name="notification_date" data-toggle="tooltip" data-placement="bottom" title="Notification Date" value="{{old('notification_date', date('l d F Y',strtotime($notification->notification_date)))}}" />
                            @error('notification_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('notification_date') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group m-b-40">
                            <input type="radio" class="check" {{($notification->is_active == 1) ? 'checked=""' : ''}} id="active_radio" name="is_active" value="1">
                            <label for="active_radio">Active</label>
                            <input type="radio" {{($notification->is_active == 0) ? 'checked=""' : ''}} class="check" id="inactive_radio"  name="is_active" value="0">
                            <label for="inactive_radio">Inactive</label>
                            @error('is_active')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('is_active') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Submit</button>
                                            <a href="{{route('admin.app_notification.list')}}" class="btn btn-inverse">Cancel</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->
</div>
@endsection
@section('script')
<script src="{{asset('ckeditor/ckeditor.js')}}"></script>
<script type="text/javascript" src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/moment/moment.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js')}}"></script>
<script type="text/javascript">
    CKEDITOR.replace('content');
    // CKEDITOR.on('instanceReady', function () {
    //     $.each(CKEDITOR.instances, function (instance) {
    //         CKEDITOR.instances[instance].document.on("keyup", CK_jQ);
    //         CKEDITOR.instances[instance].document.on("paste", CK_jQ);
    //         CKEDITOR.instances[instance].document.on("keypress", CK_jQ);
    //         CKEDITOR.instances[instance].document.on("blur", CK_jQ);
    //         CKEDITOR.instances[instance].document.on("change", CK_jQ);
    //     });
    // });
    $(document).ready(function(){
        $('#edit_app_notification').validate({
            ignore:[],
            wrapper: "span",
            errorClass:"has-danger",
            errorPlacement: function(label, element) {
                label.addClass('help-inline');
                if (element.attr("type") == "radio") {
                    $(element).parents('.controls').append(label)
                } else {
                    label.insertAfter(element);
                }
            },
            highlight: function(element, errorClass, validClass) {
                $(element).closest('.control-group').addClass(errorClass).removeClass(validClass);
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).closest('.control-group').removeClass(errorClass).addClass(validClass);
            },
            rules: {
                content:{
                    required:true,
                },
                title:{
                    required:true,
                },
                notification_date:{
                    required:true,
                }
             },
            messages: {
                content:{
                    required:"Please enter content."
                },
                title:{
                    required:"Please enter title.",
                },
                notification_date:{
                    required:"Please select notification date.",
                }
            }
        });
        $('.singledate').bootstrapMaterialDatePicker({ format: 'dddd DD MMMM Y' ,weekStart: 0, time: false});
        $('.singledate').bootstrapMaterialDatePicker('setDate', moment());
    });
</script>
@endsection