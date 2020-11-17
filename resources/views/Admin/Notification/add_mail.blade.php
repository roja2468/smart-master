@extends('Admin.app')
@section('title')
Mail Notification
@endsection
@section('style')
<link href="{{asset('assets/plugins/select2/dist/css/select2.min.css')}}" rel="stylesheet" type="text/css" />
<style type="text/css">
    .hidden{
        display: none;
    }
    .block{
        display: inherit;
    }
</style>
@endsection
@section('content')
<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-8 align-self-center">
            <h3 class="text-themecolor m-b-0 m-t-0">Send Mail Notification</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                <li class="breadcrumb-item active">Send Mail Notification</li>
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
                    <form class="m-t-40" method="post" action="{{route('admin.mail_notification.sendMailPushNotification')}}" id="send_push_notification_form" enctype="multipart/form-data">
                    	@csrf
                        <div class="form-group m-b-40">
                            <label for="type">Type</label>
                            <select id="type" onchange="getType(this)" data-toggle="tooltip" data-placement="bottom" class="select2 m-b-10 form-control" title="Select Type!" name="type">
                                <option value="">-select type-</option>
                                <option value="free_users">Free users</option>
                                <option value="paid_users">Paid users</option>
                                <option value="all_users">All users</option>
                                <option value="customised_users">Customised users</option>
                            </select>
                            <span class="bar"></span>
                            @error('type')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('type') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div id="users_selection" class="form-group m-b-40 hidden">
                            <label for="user_list">User</label>
                            <select multiple="" id="user_list" class="select2 m-b-10 form-control" title="Select Users!" style="width: 100%" name="user_id[]">
                                @foreach($User as $value)
                                    <option value="{{$value->id}}">{{$value->f_name}} - {{$value->phone}}</option>
                                @endforeach
                            </select>
                            <span class="bar"></span>
                            <span class="errr"></span>
                            @error('user_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('user_id') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group m-b-40">
                            <label for="title">Subject</label>
                            <input type="text" class="form-control" name="title" id="title" value="{{ old('title') }}" data-toggle="tooltip" data-placement="bottom" title="Enter Title!">
                            <span class="bar"></span>
                            @error('title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('title') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group m-b-40">
                            <label for="mail_body">Mail Body</label>
                            <textarea class="form-control" name="description" id="mail_body" value="{{ old('description') }}" data-toggle="tooltip" data-placement="bottom" title="Enter Description!"></textarea>
                            <span class="bar"></span>
                            @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('description') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Send</button>
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
<script src="{{asset('assets/plugins/select2/dist/js/select2.full.min.js')}}" type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
<script src="{{asset('ckeditor/ckeditor.js')}}"></script>
<script type="text/javascript">
    CKEDITOR.replace('mail_body');
    $("#user_list").select2({
        placeholder:"-select users-",
    });
    $(document).ready(function(){
        $('#send_push_notification_form').validate({
            wrapper: "span",
            errorClass:"has-danger",
            errorPlacement: function(label, element) {
                label.addClass('help-inline');
                if(element.attr("name") == "description") {
                    label.appendTo( element.parent("div"));
                }else if (element.attr("name") == "user_id[]") {
                    $(element).parent('div').find('.errr').append(label)
                }else {
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
                title:{
                    required:true,
                },
                'user_id[]':{
                    required:true,
                },
                description:{
                    required:true,
                },
                type:{
                    required:true,
                }
             },
            messages: {
                title:{
                    required:"Please enter title."
                },
                'user_id[]':{
                    required:"Please select users.",
                },
                description:{
                    required:"Please enter description."
                },
                type:{
                    required:"Please select type."
                },
            }
        });
    });
    function getType(ele)
    {
        if(ele.value == 'customised_users')
        {
            $('#users_selection').removeClass('hidden');
            $('#users_selection').addClass('block');
        }
        else
        {
            $('#users_selection').addClass('hidden');
            $('#users_selection').removeClass('block');
        }
    }
</script>
@endsection