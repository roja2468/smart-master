@extends('Admin.app')
@section('title')
Topic Document
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
            <h3 class="text-themecolor m-b-0 m-t-0">Add Topic Document</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                <li class="breadcrumb-item active">Add Topic Document</li>
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
                    <form class="m-t-40" method="post" action="{{route('admin.topic.document.save')}}" id="add_topic" enctype="multipart/form-data">
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
                            <label for="title">Topic Document Title</label>
                            <input type="text" class="form-control" name="title" id="title" value="{{ old('title') }}" data-toggle="tooltip" data-placement="bottom" title="Enter Topic Document Title!">
                            <span class="bar"></span>
                            @error('title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('title') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group m-b-40">
                            <label for="topic_document_image">Topic Document Image</label>
                            <input type="file" id="topic_document_image" required="" data-max-file-size="10M" data-errors-position="outside" data-allowed-file-extensions="jpg jpeg gif png" name="topic_document_image" data-toggle="tooltip" data-show-remove="false" data-placement="bottom" title="Select Topic Document Image!" class="dropify"/>
                            <span class="bar"></span>
                            @error('topic_document_image')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('topic_document_image') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group m-b-40">
                            <label for="document">Topic Document</label>
                            <input type="file" id="document" required="" data-max-file-size="10M" data-errors-position="outside" data-allowed-file-extensions="jpg jpeg gif png pdf" name="document" data-toggle="tooltip" data-show-remove="false" data-placement="bottom" title="Select Topic Document!" class="dropify"/>
                            <span class="bar"></span>
                            @error('document')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('document') }}</strong>
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
                                            <a href="{{route('admin.topic.document.list')}}" class="btn btn-inverse">Cancel</a>
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
                    $(element).parent('div').parent('div').find('.dropify-errors-container ul').append(label);
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
                topic_document_image:{
                    required:true,
                },
                document:{
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
                topic_document_image:{
                    required:"Please select image."
                },
                document:{
                    required:"Please select document."
                }
            }
        });
    });
</script>
@endsection