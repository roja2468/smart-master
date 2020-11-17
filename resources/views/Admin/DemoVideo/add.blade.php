@extends('Admin.app')
@section('title')
Demo Video
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
    .ifram_section{
        height: 150px;
    }
    .dropify-wrapper {
        height: 200px !important;
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
            <h3 class="text-themecolor m-b-0 m-t-0">Add Demo Video</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                <li class="breadcrumb-item active">Add Demo Video</li>
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
                    <form class="m-t-40" method="post" action="{{route('admin.demo.video.save')}}" id="add_demo_video" enctype="multipart/form-data">
                    	@csrf
                        <div class="form-group m-b-40">
                            <label for="title">Package</label>
                            <select class="select2 m-b-10 package_id form-control" title="Select Package!" data-toggle="tooltip" data-placement="bottom" name="package_id">
                                <option value="">-select package-</option>
                                @foreach($Packages as $Package)
                                    <option value="{{$Package->id}}">{{$Package->title}}</option>
                                @endforeach
                            </select>
                            <span class="bar"></span>
                            @error('package_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('package_id') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <a href="javascript:void(0)" data-toggle="tooltip" title="Add More" onclick="add_more()" class="btn btn-info btn-circle btn-lg float-right dropify-btn"><i class="fas fa-plus"></i></a>
                        <div style="clear: both;"></div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group m-b-40">
                                    <label for="image_0">Image</label>
                                    <input type="file" id="image_0" required="" data-max-file-size="10M" data-errors-position="outside" data-allowed-file-extensions="jpg jpeg gif png" name="image[0]" data-toggle="tooltip" data-show-remove="false" data-placement="bottom" title="Select Image!" class="dropify"/>
                                    <span class="bar"></span>
                                    @error('image')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('image') }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group m-b-40">
                                    <label for="file_0">Video Id</label>
                                    <input type="text" class="form-control file_validation" onkeyup="GetYoutubeVideo(this);" name="file[0]" id="file_0" value="{{ old('file[0]') }}" data-toggle="tooltip" data-placement="bottom" title="Enter Video Id!">
                                    <iframe width="100%" class="ifram_section" style="display: none;margin-top: 10px;" height="315" src="" onerror="BlankIframe(this);"></iframe> 
                                    <span class="bar"></span>
                                    @error('file')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('file') }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="file_container"></div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Submit</button>
                                            <a href="{{route('admin.demo.video.list')}}" class="btn btn-inverse">Cancel</a>
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
        $('#add_demo_video').on('submit', function(event) {
            $('.dropify').each(function() {
                $(this).rules("add", 
                    {
                        required: true
                    })
            }); 
            $('.file_validation').each(function() {
                $(this).rules("add", 
                    {
                        required: true
                    })
            });           
            if($('#add_demo_video').validate().form()) {
                console.log("validates");
            } else {
                console.log("does not validate");
            }
        })

        $('#add_demo_video').validate({
            wrapper: "span",
            errorClass:"has-danger",
            errorPlacement: function(label, element) {
                label.addClass('help-inline');
                if (element.attr("type") == "radio") {
                    $(element).parents('.controls').append(label)
                } else if(element.hasClass('dropify')){
                    label.insertAfter(element.closest('div'));
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
                package_id: {
                    required:true,
                }
            },
            messages: {
                package_id: {
                    required:"Please select package.",
                }
            }
        });
    });
    var i=1;
    function add_more(){
        var html = '<div><a href="javascript:void(0)" data-toggle="tooltip" title="Remove" onclick="remove_add(this)" class="btn btn-danger dropify-btn btn-circle btn-lg float-right"><i class="fa fa-times"></i></a><div style="clear: both;"></div><div class="row"><div class="col-md-6"><div class="form-group m-b-40"><label for="image_'+i+'">Image</label><input type="file" name="image['+i+']" id="image_'+i+'" data-max-file-size="10M" data-errors-position="outside" data-allowed-file-extensions="jpg jpeg gif png" data-toggle="tooltip" data-show-remove="false" data-placement="bottom" title="Select Image!" class="dropify"/><span class="bar"></span></div></div><div class="col-md-6"><div class="form-group m-b-40"><label for="file_'+i+'">Video Id</label><input type="text" class="form-control file_validation" onkeyup="GetYoutubeVideo(this);" name="file['+i+']" id="file_'+i+'" data-toggle="tooltip" data-placement="bottom" title="Enter Video Id!"><iframe width="100%" class="ifram_section" style="display: none;margin-top: 10px;" height="315" src="" onerror="BlankIframe(this);"></iframe><span class="bar"></span></div></div></div></div>';
        i++;
        $('.file_container').append(html);
        $('[data-toggle="tooltip"]').tooltip({
            "html": true,
            "delay": {"show": 100, "hide": 0},
        });
        var drEvent = $('.dropify').dropify();
    }
    function remove_add(ele){
        $(ele).parent('div').remove();
        i--;
    }
    function GetYoutubeVideo(ele)
    {
        if(ele.value)
        {
            $(ele).parent().find('.ifram_section').show();
            $(ele).parent().find('.ifram_section').attr('src','https://www.youtube.com/embed/'+ele.value);
        }
        else
        {
            $(ele).parent().find('.ifram_section').hide();
            $(ele).parent().find('.ifram_section').attr('src','');
        }
    }
    function BlankIframe(ele)
    {
        $(ele).hide();
    }
</script>
@endsection