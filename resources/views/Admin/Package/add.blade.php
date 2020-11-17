@extends('Admin.app')
@section('title')
Package
@endsection
@section('style')
<link rel="stylesheet" href="{{asset('assets/plugins/dropify/dist/css/dropify.min.css')}}">
@endsection
@section('content')
<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-8 align-self-center">
            <h3 class="text-themecolor m-b-0 m-t-0">Add Package</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                <li class="breadcrumb-item active">Add Package</li>
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
                    <form class="m-t-40" method="post" action="{{route('admin.package.save')}}" id="add_package" enctype="multipart/form-data">
                    	@csrf
                        <div class="form-group m-b-40">
                            <label for="time_frame">Package Type</label>
                            <select class="select2 m-b-10 parent_category form-control" title="Select Category!" data-toggle="tooltip" data-placement="bottom" name="time_frame">
                                <option value="">-select time frame-</option>
                                <option value="1">1 Month</option>
                                <option value="2">3 Month</option>
                                <option value="3">6 Month</option>
                                <option value="4">1 Year</option>
                            </select>
                            @error('time_frame')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('time_frame') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group m-b-40">
                            <label for="title">Category</label>
                            <select class="select2 m-b-10 parent_category form-control" title="Select Category!" data-toggle="tooltip" data-placement="bottom" name="parent_category">
                                <option value="">-select category-</option>
                                @foreach($Category as $value)
                                    <option value="{{$value->id}}">{{$value->title}}</option>
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
                            <label for="title">Title</label>
                            <input type="text" class="form-control" name="title" id="title" value="{{ old('title') }}" data-toggle="tooltip" data-placement="bottom" title="Enter Package Name!">
                            <span class="bar"></span>
                            @error('title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('title') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group m-b-40">
                            <label for="amount">Package Prize</label>
                            <input type="text" class="form-control" onkeypress="return restrictAlphabets(event);" name="amount" id="amount" value="{{ old('amount') }}" data-toggle="tooltip" data-placement="bottom" title="Enter Package Prize!">
                            <span class="bar"></span>
                            @error('amount')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('amount') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group m-b-40">
                            <label for="package_mrp">Package MRP</label>
                            <input type="text" class="form-control" onkeypress="return restrictAlphabets(event);" name="package_mrp" id="package_mrp" value="{{ old('package_mrp') }}" data-toggle="tooltip" data-placement="bottom" title="Enter Package MRP!">
                            <span class="bar"></span>
                            @error('package_mrp')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('package_mrp') }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="form-group m-b-40">
                            <label for="package_offer">Package Offer</label>
                            <input type="text" class="form-control" name="package_offer" id="package_offer" value="{{ old('package_offer') }}" data-toggle="tooltip" data-placement="bottom" title="Enter Package Offer!">
                            <span class="bar"></span>
                            @error('package_offer')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('package_offer') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group m-b-40">
                            <label for="author_name">Author Name</label>
                            <input type="text" class="form-control" name="author_name" id="author_name" value="{{ old('author_name') }}" data-toggle="tooltip" data-placement="bottom" title="Enter Author Name!">
                            <span class="bar"></span>
                            @error('author_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('author_name') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group m-b-40">
                            <label for="author_profile_pic">Author Profile</label>
                            <input type="file" id="author_profile_pic" data-max-file-size="10M" data-errors-position="outside" data-allowed-file-extensions="jpg jpeg gif png" name="author_profile_pic" data-toggle="tooltip" data-placement="bottom" title="Select Image!" class="dropify"/>
                            @error('author_profile_pic')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('author_profile_pic') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group m-b-40">
                            <label for="author_designation">Author Designation</label>
                            <input type="text" class="form-control" name="author_designation" id="author_designation" value="{{ old('author_designation') }}" data-toggle="tooltip" data-placement="bottom" title="Enter Author Designation!">
                            <span class="bar"></span>
                            @error('author_designation')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('author_designation') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group m-b-40">
                            <label for="author_qualification">Author Qualification</label>
                            <textarea id="author_qualification" class="form-control" name="author_qualification" data-toggle="tooltip" data-placement="bottom" title="Enter Author Qualification!">{!! old('author_qualification') !!}</textarea>
                            <span class="bar"></span>
                            @error('author_qualification')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('author_qualification') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group m-b-40">
                            <label for="about_course_description">About Course Description</label>
                            <textarea id="about_course_description" class="form-control" name="about_course_description" data-toggle="tooltip" data-placement="bottom" title="Enter About Course Description!"></textarea>
                            @error('about_course_description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('about_course_description') }}</strong>
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
                                            <a href="{{route('admin.package.list')}}" class="btn btn-inverse">Cancel</a>
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
<script type="text/javascript" src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/plugins/moment/moment.js')}}"></script>
<script src="{{asset('ckeditor/ckeditor.js')}}"></script>
<script src="{{asset('assets/plugins/dropify/dist/js/dropify.min.js')}}"></script>
<script type="text/javascript">
    var drEvent = $('.dropify').dropify();
    drEvent.on('dropify.beforeClear', function(event, element) {
    });
    drEvent.on('dropify.afterClear', function(event, element) {
    });
    drEvent.on('dropify.errors', function(event, element) {
    });
    CKEDITOR.replace('about_course_description');
    CKEDITOR.replace('author_qualification');
    $(document).ready(function(){
        $('#add_package').validate({
            ignore:[],
            wrapper: "span",
            errorClass:"has-danger",
            errorPlacement: function(label, element) {
                label.addClass('help-inline');
                if(element.attr("name") == "about_course_description") {
                    label.appendTo( element.parent("div"));
                } else if(element.attr("name") == "author_qualification") {
                    label.appendTo( element.parent("div"));
                } else if (element.attr("type") == "radio") {
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
                parent_category: {
                    required:true,
                },
                title:{
                    required:true,
                },
                amount:{
                    required:true,
                    number: true
                },
                time_frame:{
                    required:true,
                },
                package_mrp:{
                    required:true,
                },
                package_offer:{
                    required:true,
                },
                about_course_description:{
                    required:true,
                },
                author_name:{
                    required:true,
                },
                author_designation:{
                    required:true,
                },
                author_qualification:{
                    required:true,
                },
             },
            messages: {
                parent_category: {
                    required:"Please select category",
                },
                title:{
                    required:"Please enter title."
                },
                time_frame:{
                    required:"Please select time frame."
                },
                amount:{
                    required:"Please enter amount.",
                    number: "Allow only numeric value."
                },
                package_mrp:{
                    required:"Please enter package MRP.",
                },
                package_offer:{
                    required:"Please enter package offer.",
                },
                about_course_description:{
                    required:"Please enter about course description.",
                },
                author_name:{
                    required:"Please enter author name.",
                },
                author_designation:{
                    required:"Please enter author designation.",
                },
                author_qualification:{
                    required:"Please enter author qualification.",
                },
            }
        });
    });
</script>
@endsection