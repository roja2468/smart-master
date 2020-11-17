@extends('Admin.app')
@section('title')
Topic Video
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
            <h3 class="text-themecolor m-b-0 m-t-0">Add Topic Video</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                <li class="breadcrumb-item active">Add Topic Video</li>
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
                    <form class="m-t-40" method="post" action="{{route('admin.topic.video.save')}}" id="add_topic" enctype="multipart/form-data">
                    	@csrf
                        <div class="form-group m-b-40">
                            <label for="topic">Topic</label>
                            <select class="select2 m-b-10 form-control" id="topic" name="topic" data-toggle="tooltip" data-placement="bottom" title="Select Topic!">
                                <option value="">-select topic-</option>
                                @foreach($Topic as $key => $topic)
                                <option value="{{$topic->id}}">{{$topic->title}}</option>
                                @endforeach
                            </select>
                            <span class="bar"></span>
                            @error('topic')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('topic') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group m-b-40">
                            <label for="title">Topic Video Title</label>
                            <input type="text" class="form-control" name="title" id="title" value="{{ old('title') }}" data-toggle="tooltip" data-placement="bottom" title="Enter Topic Video Title!">
                            <span class="bar"></span>
                            @error('title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('title') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group m-b-40">
                            <label for="topic_video_image">Topic Video Image</label>
                            <input type="file" id="topic_video_image" required="" data-max-file-size="10M" data-errors-position="outside" data-allowed-file-extensions="jpg jpeg gif png" name="topic_video_image" data-toggle="tooltip" data-show-remove="false" data-placement="bottom" title="Select Topic Video Image!" class="dropify"/>
                            <span class="bar"></span>
                            @error('topic_video_image')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('topic_video_image') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group m-b-40">
                            <label for="video">Topic Video Id</label>
                            <input type="text" class="form-control" onkeyup="GetYoutubeVideo(this);" name="video" id="video" value="{{ old('video') }}" data-toggle="tooltip" data-placement="bottom" title="Enter Topic Video Id!">
                            <iframe width="500" class="ifram_section" style="display: none;margin-top: 10px;" height="315" src="" onerror="BlankIframe(this);"></iframe> 
                            <span class="bar"></span>
                            @error('video')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('video') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group m-b-40">
                            <input type="radio" class="check" id="paid_radio" checked="" name="is_paid" value="1">
                            <label for="paid_radio">Paid</label>
                            <input type="radio" class="check" id="free_radio" name="is_paid" value="0">
                            <label for="free_radio">Free</label>
                            @error('is_paid')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('is_paid') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Submit</button>
                                            <a href="{{route('admin.topic.video.list')}}" class="btn btn-inverse">Cancel</a>
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
                } else if (element.attr("type") == "file") {
                    console.log(label);
                    $('.dropify-errors-container ul').append(label);
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
                topic:{
                    required:true,
                },
                title:{
                    required:true,
                },
                topic_video_image:{
                    required:true,
                },
                video:{
                    required:true,
                }
             },
            messages: {
                topic:{
                    required:"Please select topic."
                },
                topic:{
                    required:"Please enter title."
                },
                topic_video_image:{
                    required:"Please select image."
                },
                video:{
                    required:"Please enter video id."
                }
            }
        });
    });
    function GetYoutubeVideo(ele)
    {
        if(ele.value)
        {
            $('.ifram_section').show();
            $('.ifram_section').attr('src','https://www.youtube.com/embed/'+ele.value);
        }
        else
        {
            $('.ifram_section').hide();
            $('.ifram_section').attr('src','');
        }
    }
    function BlankIframe(ele)
    {
        $(ele).hide();
    }
</script>
@endsection