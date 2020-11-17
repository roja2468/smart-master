@extends('Admin.app')
@section('title')
Topic
@endsection
@section('style')
<link href="{{asset('assets/plugins/select2/dist/css/select2.min.css')}}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{{asset('assets/plugins/dropify/dist/css/dropify.min.css')}}">
<style type="text/css">
    .btn-circle.btn-lg {
    width: 30px !important;
    height: 30px !important;
    padding: 1px 7px !important;
    font-size: 18px;
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
            <h3 class="text-themecolor m-b-0 m-t-0">Add Topic</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                <li class="breadcrumb-item active">Add Topic</li>
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
                    <form class="m-t-40" method="post" action="{{route('admin.topic.save')}}" id="add_topic" enctype="multipart/form-data">
                    	@csrf
                        <div class="form-group m-b-40">
                            <label for="parent_category">Category</label>
                            <select class="select2 m-b-10 parent_category form-control" id="parent_category" name="parent_category" data-toggle="tooltip" data-placement="bottom" title="Select Category!">
                                <option value="">-select category-</option>
                                @foreach($parentCategory as $key => $Category)
                                <option value="{{$Category->id}}">{{$Category->title}}</option>
                                @endforeach
                            </select>
                            <span class="bar"></span>
                            @error('parent_category')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('parent_category') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group m-b-40">
                            <label for="sub_category">Sub Category</label>
                            <select class="select2 m-b-10 form-control" id="sub_category" name="sub_category" data-toggle="tooltip" data-placement="bottom" title="Select Sub Category!">
                            <option value="">-select sub category-</option>
                            </select>
                            <span class="bar"></span>
                            @error('title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('title') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group m-b-40">
                            <label for="child_category">Child Category</label>
                            <select class="select2 m-b-10 child_category form-control" name="child_category" id="child_category" data-toggle="tooltip" data-placement="bottom" title="Select Child Category!">
                            <option value="">-select child category-</option>
                            </select>
                            <span class="bar"></span>
                            @error('child_category')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('child_category') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group m-b-40">
                            <label for="title">Topic</label>
                            <input type="text" class="form-control" name="title" id="title" value="{{ old('title') }}" data-toggle="tooltip" data-placement="bottom" title="Enter Topic!">
                            <span class="bar"></span>
                            @error('title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('title') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Submit</button>
                                            <a href="{{route('admin.topic.list')}}" class="btn btn-inverse">Cancel</a>
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
<script src="{{asset('assets/plugins/dropify/dist/js/dropify.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
<script type="text/javascript">
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
        $('#add_topic').validate({
            wrapper: "span",
            errorClass:"has-danger",
            errorPlacement: function(label, element) {
                label.addClass('help-inline');
                if (element.attr("type") == "radio") {
                    $(element).parents('.controls').append(label)
                } else if (element.attr("type") == "file") {}else {
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
                parent_category:{
                    required:true,
                },
                sub_category:{
                    required:true,
                },
                child_category:{
                    required:true,
                },
                title:{
                    required:true,
                }
             },
            messages: {
                parent_category:{
                    required:"Please select category."
                },
                sub_category:{
                    required:"Please select sub category."
                },
                child_category:{
                    required:"Please select child category."
                },
                title:{
                    required:"Please enter title."
                }
            }
        });
    });
    var document_i = 1;
    function add_more_document()
    {
        var html = '<div class="col-md-6"><div class="form-group m-b-40"><a href="javascript:void(0)" data-toggle="tooltip" title="Remove" onclick="remove_add_more_document(this)" class="btn btn-danger dropify-btn btn-circle btn-lg float-right"><i class="fa fa-times"></i></a><label for="document_title_'+document_i+'">Document Title</label><input type="text" class="form-control" name="document_title_'+document_i+'" id="document_title_'+document_i+'" data-toggle="tooltip" data-placement="bottom" title="Enter Document Title!"><span class="bar"></span></div><div class="form-group m-b-40"><input type="file" id="input-file-now-custom-1" data-max-file-size="2M" data-errors-position="outside" data-allowed-file-extensions="jpg jpeg gif png pdf" name="topic_document[]" data-toggle="tooltip" data-placement="bottom" title="Select Topic Video!" class="dropify"  /></div></div>';
        document_i++;
        $('#topic_document_container').append(html);
        $('.dropify').dropify();
        $('[data-toggle="tooltip"]').tooltip({
            "html": true,
            "delay": {"show": 100, "hide": 0},
        });
    }
    var video_i = 1;
    function add_more_video()
    {
        var html = '<div  class="col-md-6"><div class="form-group m-b-40"><a href="javascript:void(0)" data-toggle="tooltip" title="Remove" onclick="remove_add_more_video(this)" class="btn btn-danger dropify-btn btn-circle btn-lg float-right"><i class="fa fa-times"></i></a><label for="video_title_'+video_i+'">Video Title</label><input type="text" required="" class="form-control" name="video_title_'+video_i+'" id="video_title_'+video_i+'" data-toggle="tooltip" data-placement="bottom" title="Enter Video Title!"><span class="bar"></span></div><div class="form-group m-b-40"><input type="file" id="input-file-now-custom-1" data-max-file-size="2M" data-errors-position="outside" data-allowed-file-extensions="jpg jpeg gif png mp4 3gp avi m4a" required="" name="topic_video['+video_i+']" data-toggle="tooltip" data-placement="bottom" title="Select Topic Video!" class="dropify"  /></div></div>';
        video_i++;
        $('#topic_video_container').append(html);
        $('.dropify').dropify();
        $('[data-toggle="tooltip"]').tooltip({
            "html": true,
            "delay": {"show": 100, "hide": 0},
        });
        $('#add_topic').validate();
    }
    function remove_add_more_document(ele)
    {
        document_i--;
        $(ele).parent('div').parent('div').remove();
        $('body>.tooltip').remove();
    }
    function remove_add_more_video(ele)
    {
        video_i--;
        $(ele).parent('div').parent('div').remove();
        $('body>.tooltip').remove();
    }
</script>
@endsection