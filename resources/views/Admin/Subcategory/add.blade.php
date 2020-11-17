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
            <h3 class="text-themecolor m-b-0 m-t-0">Add Sub Category</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                <li class="breadcrumb-item active">Add Sub Category</li>
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
                    <form class="m-t-40" method="post" action="{{route('admin.sub_categories.save')}}" id="add_category" enctype="multipart/form-data">
                    	@csrf
                        
                        <div class="form-group m-b-40">
                            <label for="title">Category</label>
                            <select class="select2 m-b-10 parent_category form-control" title="Select Category!" data-toggle="tooltip" data-placement="bottom" name="parent_category">
                                <option value="">-select category-</option>
                                @foreach($parentCategory as $value)
                                    <option value="{{$value->id}}">{{$value->title}}</option>
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
                            <label for="title">Sub Category</label>
                            <input type="text" class="form-control" name="title" id="title" value="{{ old('title') }}" data-toggle="tooltip" data-placement="bottom" title="Enter Category!">
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
                                            <a href="{{route('admin.sub_categories.list')}}" class="btn btn-inverse">Cancel</a>
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
    /*$(".parent_category").select2({
        placeholder:"select category",
        allowClear: true
    });*/
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
        $('#add_category').validate({
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
                parent_category: {
                    required:true,
                },
                title:{
                    required:true,
                    remote: {
                        url : "{!! route('admin.sub_categories.unique') !!}",
                        data: {
                            title: function () {
                                return $("input[name='title']").val();
                            }
                        },
                        type : "get",
                        dataFilter: function (data) {
                            var json = JSON.parse(data);
                            if (json.data == true) {
                                return "\"" + "That category is taken" + "\"";
                            } else {
                                return 'true';
                            }
                        }
                    }
                }
             },
            messages: {
                parent_category: {
                    required:"Please select parent category",
                },
                title:{
                    required:"Please enter category title"
                }
            }
        });
    });
    
</script>
@endsection