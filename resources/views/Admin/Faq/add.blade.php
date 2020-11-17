@extends('Admin.app')
@section('title')
Faq
@endsection
@section('style')
@endsection
@section('content')
<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-8 align-self-center">
            <h3 class="text-themecolor m-b-0 m-t-0">Add Faq</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                <li class="breadcrumb-item active">Add Faq</li>
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
                    <form class="m-t-40" method="post" action="{{route('admin.faq.save')}}" id="add_faq" enctype="multipart/form-data">
                    	@csrf
                        <div class="form-group m-b-40">
                            <label for="title">Faq Title</label>
                            <input id="title" class="form-control" name="title" data-toggle="tooltip" data-placement="bottom" value="{{old('title')}}" title="Enter Title!">
                            @error('title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('title') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group m-b-40">
                            <label for="content">Faq</label>
                            <textarea id="content" class="form-control" name="content" data-toggle="tooltip" data-placement="bottom" title="Enter Content!"></textarea>
                            @error('content')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('content') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group m-b-40">
                            <input type="radio" class="check" id="active_radio" checked="" name="is_active" value="1">
                            <label for="active_radio">Active</label>
                            <input type="radio" class="check" id="inactive_radio" name="is_active" value="0">
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
                                            <a href="{{route('admin.faq.list')}}" class="btn btn-inverse">Cancel</a>
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
<script type="text/javascript">
    CKEDITOR.replace('content');
    CKEDITOR.on('instanceReady', function () {
        $.each(CKEDITOR.instances, function (instance) {
            CKEDITOR.instances[instance].document.on("keyup", CK_jQ);
            CKEDITOR.instances[instance].document.on("paste", CK_jQ);
            CKEDITOR.instances[instance].document.on("keypress", CK_jQ);
            CKEDITOR.instances[instance].document.on("blur", CK_jQ);
            CKEDITOR.instances[instance].document.on("change", CK_jQ);
        });
    });
    $(document).ready(function(){
        $('#add_faq').validate({
            ignore:[],
            wrapper: "span",
            errorClass:"has-danger",
            errorPlacement: function(error, element) {
                if(element.attr("name") == "content") {
                    error.appendTo( element.parent("div"));
                }
                else if (element.attr("type") == "radio") {
                    $(element).parents('.controls').append(error)
                }
                else {
                    error.insertAfter(element);
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
                }
             },
            messages: {
                content:{
                    required:"Please enter content."
                },
                title:{
                    required:"Please enter title.",
                }
            }
        });
    });
</script>
@endsection