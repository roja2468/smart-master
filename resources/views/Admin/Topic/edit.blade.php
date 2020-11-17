@extends('Admin.app')
@section('title')
Topic
@endsection
@section('style')
<link href="{{asset('assets/plugins/select2/dist/css/select2.min.css')}}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{{asset('assets/plugins/dropify/dist/css/dropify.min.css')}}">
<style type="text/css">
    .container_box{
        border: 1px solid #80808061;
        padding: 20px;
        margin-bottom: 10px;
        overflow: auto;
    }
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
            <h3 class="text-themecolor m-b-0 m-t-0">Edit Topic</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                <li class="breadcrumb-item active">Edit Topic</li>
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
                    <form class="m-t-40" method="post" action="{{route('admin.topic.update',$Topic->id)}}" id="edit_topic" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group m-b-40">
                            <label for="parent_category">Category</label>
                            <select class="select2 m-b-10 parent_category form-control" id="parent_category" name="parent_category" data-toggle="tooltip" data-placement="bottom" title="Select Category!">
                                <option value="">-select category-</option>
                                @foreach($parentCategory as $key => $Category)
                                <option {{($Topic->category_id == $Category->id) ? 'selected' : ''}} value="{{$Category->id}}">{{$Category->title}}</option>
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
                                <option  value="">-select sub category-</option>
                                @foreach($subCategory as $key => $Category)
                                <option {{($Topic->sub_category_id == $Category->id) ? 'selected=""' : ''}} value="{{$Category->id}}">{{$Category->title}}</option>
                                @endforeach
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
                                @foreach($childCategory as $key => $Category)
                                <option {{($Topic->child_category_id == $Category->id) ? 'selected=""' : ''}} value="{{$Category->id}}">{{$Category->title}}</option>
                                @endforeach
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
                            <input type="text" class="form-control" name="title" id="title" value="{{$Topic->title}}" data-toggle="tooltip" data-placement="bottom" title="Enter Topic!">
                            <span class="bar"></span>
                            @error('title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('title') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <!-- <p>Topic Document Section</p> -->
                        {{-- <div id="topic_document_container" class="container_box row">
                            <div class="col-md-12 add_btn">
                                <a href="javascript:void(0)" data-toggle="tooltip" title="Add More" onclick="add_more_document()" class="btn btn-info btn-circle btn-lg dropify-btn"><i class="fas fa-plus"></i> </a>
                            </div>
                            @forelse($TopicDocument as $key => $document)
                            <div class="col-md-6">
                                <div class="form-group m-b-40">
                                    <a href="javascript:void(0)" data-toggle="tooltip" title="Remove" onclick="remove_add_more_document_ajax(this,'{{$document->id}}')" class="btn btn-danger dropify-btn btn-circle btn-lg float-right"><i class="fa fa-times"></i></a>
                                    <input type="hidden" value="{{$document->id}}" name="docuement_id[]">
                                    <label for="document_title_{{$key}}">Document Title</label>
                                    <input type="text"  class="form-control document_title" name="document_edit_title_{{$key}}" value="{{$document->title}}" id="document_title_{{$key}}" data-toggle="tooltip" data-placement="bottom" title="Enter Document Title!">
                                    <span class="bar"></span>
                                    @error('document_title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('document_title') }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group m-b-40">
                                    <input type="file" id="input-file-now-custom-1" data-max-file-size="2M" data-errors-position="outside" data-allowed-file-extensions="jpg jpeg gif png pdf" name="topic_document_edit{{$key}}" data-toggle="tooltip" data-show-remove="false" data-placement="bottom" title="Select Topic Video!" value="{{asset('uploads/topic_document/')}}/{{$document->document}}" data-default-file="{{asset('uploads/topic_document/')}}/{{$document->document}}" class="dropify"  />
                                    @error('topic_document')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('topic_document') }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            @empty
                            @endforelse    
                        </div> 
                        <!-- <p>Topic Video Section</p> -->
                        <!-- <div id="topic_video_container" class="container_box row">
                            <div class="col-md-12 add_btn">
                                <a href="javascript:void(0)" data-toggle="tooltip" title="Add More" onclick="add_more_video()" class="btn btn-info btn-circle btn-lg dropify-btn"><i class="fas fa-plus"></i> </a>
                            </div>
                            @forelse($TopicVideo as $key => $video)
                            <div class="col-md-6">
                                <div class="form-group m-b-40">
                                    <a href="javascript:void(0)" data-toggle="tooltip" title="Remove" onclick="remove_add_more_video_ajax(this,'{{$video->id}}')" class="btn btn-danger dropify-btn btn-circle btn-lg float-right"><i class="fa fa-times"></i></a>
                                    <input type="hidden" value="{{$video->id}}" name="video_id[]">
                                    <label for="video_title_{{$key}}">Video Title</label>
                                    <input type="text" required="" class="form-control video_title" value="{{$video->title}}" name="video_title_edit_{{$key}}" id="video_title_{{$key}}" data-toggle="tooltip" data-placement="bottom" title="Enter Video Title!">
                                    <span class="bar"></span>
                                    @error('video_title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('video_title') }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group m-b-40">
                                    <input type="file" id="input-file-now-custom-1" data-max-file-size="2M" data-errors-position="outside" data-allowed-file-extensions="jpg jpeg gif png mp4 3gp avi m4a" data-show-remove="false" name="topic_video_edit_{{$key}}" data-toggle="tooltip" data-placement="bottom" title="Select Topic Video!" value="{{asset('uploads/topic_video/')}}/{{$video->video}}" data-default-file="{{asset('uploads/topic_video/')}}/{{$video->video}}" class="dropify"  />
                                    @error('topic_video')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('topic_video') }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            @empty
                            @endforelse 
                        </div> -->  --}}
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
        $('#edit_topic').validate({
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
    // var document_i = $('.document_title').length;
    var document_i = 0;
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
    // var video_i = $('.video_title').length;
    var video_i = 0;
    function add_more_video()
    {
        var html = '<div class="col-md-6"><div class="form-group m-b-40"><a href="javascript:void(0)" data-toggle="tooltip" title="Remove" onclick="remove_add_more_video(this)" class="btn btn-danger dropify-btn btn-circle btn-lg float-right"><i class="fa fa-times"></i></a><label for="video_title_'+video_i+'">Video Title</label><input type="text" required="" class="form-control" name="video_title_'+video_i+'" id="video_title_'+video_i+'" data-toggle="tooltip" data-placement="bottom" title="Enter Video Title!"><span class="bar"></span></div><div class="form-group m-b-40"><input type="file" id="input-file-now-custom-1" data-max-file-size="10M" data-errors-position="outside" data-allowed-file-extensions="jpg jpeg gif png mp4 3gp avi m4a" required="" name="topic_video['+video_i+']" data-toggle="tooltip" data-placement="bottom" title="Select Topic Video!" class="dropify"  /></div></div>';
        video_i++;
        $('#topic_video_container').append(html);
        $('.dropify').dropify();
        $('[data-toggle="tooltip"]').tooltip({
            "html": true,
            "delay": {"show": 100, "hide": 0},
        });
        $('#edit_topic').validate();
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
    function remove_add_more_document_ajax(ele,id)
    {
        var error;
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "POST",
                    headers: {'X-CSRF-Token': Laravel.csrfToken},
                    url: '{{ route("admin.topic.document.delete") }}',
                    dataType : 'json',
                    data: {id:id},
                    success: function(response) {
                        if(response.succsess == true)
                        {
                            Swal.fire(
                              'Deleted!',
                              'Document has been deleted.',
                              'success'
                            )
                            $(ele).parent('div').parent('div').remove();
                            $('body>.tooltip').remove();
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
        })
    }
    function remove_add_more_video_ajax(ele,id)
    {
        var error;
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "POST",
                    headers: {'X-CSRF-Token': Laravel.csrfToken},
                    url: '{{ route("admin.topic.video.delete") }}',
                    dataType : 'json',
                    data: {id:id},
                    success: function(response) {
                        if(response.succsess == true)
                        {
                            Swal.fire(
                              'Deleted!',
                              'Video has been deleted.',
                              'success'
                            )
                            $(ele).parent('div').parent('div').remove();
                            $('body>.tooltip').remove();
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
        })
    }
</script>
@endsection