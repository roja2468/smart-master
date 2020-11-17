@extends('Admin.app')
@section('title')
Referral Amount
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
            <h3 class="text-themecolor m-b-0 m-t-0">Edit Referral Amount</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                <li class="breadcrumb-item active">Edit Referral Amount</li>
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
                    <form class="m-t-40" method="post" action="{{route('admin.referral_amount.update',$ReferralAmount->id)}}" id="edit_referral_amount" enctype="multipart/form-data">
                    	@csrf
                        <div class="form-group m-b-40">
                            <label for="amount">Referral Amount</label>
                            <input type="text" class="form-control" onkeypress="return restrictAlphabets(event);" name="amount" id="amount" value="{{ old('amount',$ReferralAmount->amount) }}" data-toggle="tooltip" data-placement="bottom" title="Enter Referral Amount!">
                            <span class="bar"></span>
                            @error('amount')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('amount') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Update</button>
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
<script type="text/javascript">
    $(document).ready(function(){
        $('#edit_referral_amount').validate({
            ignore:[],
            wrapper: "span",
            errorClass:"has-danger",
            errorPlacement: function(label, element) {
                label.addClass('help-inline');
                label.insertAfter(element);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).closest('.control-group').addClass(errorClass).removeClass(validClass);
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).closest('.control-group').removeClass(errorClass).addClass(validClass);
            },
            rules: {
                amount:{
                    required:true,
                    number: true
                }
             },
            messages: {
                amount:{
                    required:"Please enter amount.",
                    number: "Allow only numeric value."
                }
            }
        });
    });
</script>
@endsection