@extends('Admin.app')
@section('title')
Demo Article
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
            <h3 class="text-themecolor m-b-0 m-t-0">Edit Demo Article</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                <li class="breadcrumb-item active">Edit Demo Article</li>
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
                    <form class="m-t-40" method="post" action="{{route('admin.demo.article.update',$DemoArticle->id)}}" id="edit_demo_article" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group m-b-40">
                            <label for="title">Package</label>
                            <select class="select2 m-b-10 package_id form-control" title="Select Package!" data-toggle="tooltip" data-placement="bottom" name="package_id">
                                <option value="">-select package-</option>
                                @foreach($Packages as $Package)
                                    <option {{($DemoArticle->package_id == $Package->id) ? 'selected' : ''}} value="{{$Package->id}}">{{$Package->title}}</option>
                                @endforeach
                            </select>
                            <span class="bar"></span>
                            @error('package_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('package_id') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group m-b-40">
                                    <label for="image">Image</label>
                                    <input type="file" id="image" data-max-file-size="10M" data-errors-position="outside" data-allowed-file-extensions="jpg jpeg gif png" name="image" data-toggle="tooltip" data-show-remove="false" data-placement="bottom" title="Select Image!" class="dropify" value="{{$DemoArticle->image}}" data-default-file="{{$DemoArticle->image}}"/>
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
                                    <label for="file">File</label>
                                    <input type="file" id="file" data-max-file-size="10M" data-errors-position="outside" data-allowed-file-extensions="jpg jpeg gif png" name="file" data-toggle="tooltip" data-show-remove="false" data-placement="bottom" title="Select Image!" class="dropify" value="{{$DemoArticle->file}}" data-default-file="{{$DemoArticle->file}}"/>
                                    <span class="bar"></span>
                                    @error('file')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('file') }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-b-40">
                            <input type="radio" class="check" {{($DemoArticle->is_active == 1) ? 'checked=""' : ''}} id="active_radio" name="is_active" value="1">
                            <label for="active_radio">Active</label>
                            <input type="radio" {{($DemoArticle->is_active == 0) ? 'checked=""' : ''}} class="check" id="inactive_radio"  name="is_active" value="0">
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
                                            <a href="{{route('admin.demo.article.list')}}" class="btn btn-inverse">Cancel</a>
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
        $('#edit_demo_article').validate({
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
                package_id:{
                    required:true,
                }
            },
            messages: {
                package_id:{
                    required:"Please select package."
                }
            }
        });
    });
</script>
@endsection