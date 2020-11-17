@extends('Admin.app')
@section('title')
Category
@endsection
@section('style')
<link href="{{asset('assets/plugins/select2/dist/css/select2.min.css')}}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{{asset('assets/plugins/dropify/dist/css/dropify.min.css')}}">
@endsection
@section('content')
<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-8 align-self-center">
            <h3 class="text-themecolor m-b-0 m-t-0">Update Password</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                <li class="breadcrumb-item active">Update Password</li>
            </ol>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-5">
            <div class="card">
                <div class="card-body">
                    <form class="m-t-40" method="post" action="{{route('admin.user.update',$category->id)}}" id="edit_category" enctype="multipart/form-data"> 
                          @csrf 
                          <div class="form-group">
                            <label class="col-md-12">Password</label>
                            <div class="col-md-12">
                              <input type="password" name="password" class="form-control form-control-line">
                            </div>
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                              <strong>{{ $errors->first('password') }}</strong>
                            </span>
                            @enderror
                          </div>
                          <div class="form-group">
                            <label class="col-md-12">Confirm Password</label>
                            <div class="col-md-12">
                              <input type="password" name="password_confirmation" class="form-control form-control-line">
                            </div>
                            @error('password_confirmation')
                            <span class="invalid-feedback" role="alert">
                              <strong>{{ $errors->first('password_confirmation') }}</strong>
                            </span>
                            @enderror
                          </div>
                          <div class="form-group">
                            <div class="col-sm-12">
                              <button class="btn btn-success">Change Password</button>
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
<script src="{{asset('assets/plugins/dropify/dist/js/dropify.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
<script type="text/javascript">
    $(".parent_category").select2({
        placeholder:"select category",
        allowClear: true
    });
    var drEvent = $('.dropify').dropify();

    drEvent.on('dropify.beforeClear', function(event, element) {
        // return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
    });

    drEvent.on('dropify.afterClear', function(event, element) {
        // alert('File deleted');
    });

    drEvent.on('dropify.errors', function(event, element) {
        console.log('Has Errors');
    });
    $(document).ready(function(){
        $('#edit_category').validate({
            wrapper: "span",
            errorClass:"has-danger",
            errorPlacement: function(label, element) {
                label.addClass('help-inline');
                if (element.attr("type") == "radio") {
                    $(element).parents('.controls').append(label)
                }else if (element.attr("type") == "file") {
                    $(element).parent('div').parent('div').find('.dropify-errors-container ul').append(label);
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
                title:{
                    required:true,
                },
                description:{
                    required:true,
                }
             },
            messages: {
                title:{
                    required:"Please enter title"
                },
                description:{
                    required:"Please enter description."
                }
            }
        });
    });
    
</script>
@endsection