<!DOCTYPE html>
<html lang="en">
<head>
	<title>{{ config('app.name', 'Laravel') }} Admin</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="{{asset('images/icons/favicon.ico')}}"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('auth/vendor/bootstrap/css/bootstrap.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('auth/fonts/font-awesome-4.7.0/css/font-awesome.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('auth/fonts/Linearicons-Free-v1.0.0/icon-font.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('auth/vendor/animate/animate.css')}}">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="{{asset('auth/vendor/css-hamburgers/hamburgers.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('auth/vendor/animsition/css/animsition.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('auth/vendor/select2/select2.min.css')}}">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="{{asset('auth/vendor/daterangepicker/daterangepicker.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('auth/css/util.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('auth/css/main.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('auth/css/login_page.css')}}">
<!--===============================================================================================-->


<style type="text/css">

</style>
</head>
<body>

	<section id="wrapper" class="login-register login-sidebar" style="background: #4d565b;">
        <div class="left-aside card d-flex">
            <div class="card-body align-items-center d-flex justify-content-center">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <!-- <h1 class="text-white"><i class="fa fa-globe"></i></h1> -->
                        <img src="{{asset('no-photo.png')}}" class="img-circle" width="165px" />
                    </div>
                    <div class="col-md-12 text-center">

                        <h2 class="text-white">Smart Rankers</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="login-box card d-flex">
            <div class="card-body align-items-center d-flex justify-content-center">
                <form class="form-horizontal form-material width-50pr" id="loginform" method="POST" action="{{ route('login') }}">
                    @csrf
                    @if(Session::has('message'))
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert {{ Session::get('alert-class') }} p-10">
                                {{ Session::get('message') }}
                                @if(Session::has('message_btn'))
                                    {!! Session::get('message_btn') !!}
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                    @if(Session::has('success'))
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-success">
                                {{ Session::get('success') }}
                            </div>
                        </div>
                    </div>
                    @endif
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                            <button class="close" data-dismiss="alert">×</button>
                        </div>
                    @endif
                    <h2><i class="fa fa-globe"></i>Smart Rankers Admin</h2>
                    <div class="form-group m-t-40">
                        <div class="col-xs-12">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Email">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12">
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required placeholder="Password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="form-group text-center m-t-20">
                        <div class="col-xs-12">
                            <button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light" type="submit"> {{ __('Login') }}</button>
                        </div>
                    </div>
                    
                </form>
            </div>
        </div>
    </section>
	
<!--===============================================================================================-->
	<script src="{{asset('auth/vendor/jquery/jquery-3.2.1.min.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{asset('auth/vendor/animsition/js/animsition.min.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{asset('auth/vendor/bootstrap/js/popper.js')}}"></script>
	<script src="{{asset('auth/vendor/bootstrap/js/bootstrap.min.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{asset('auth/vendor/select2/select2.min.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{asset('auth/vendor/daterangepicker/moment.min.js')}}"></script>
	<script src="{{asset('auth/vendor/daterangepicker/daterangepicker.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{asset('auth/vendor/countdowntime/countdowntime.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{asset('auth/js/main.js')}}"></script>

	<script src="{{asset('assets/js/jquery.validate.min.js')}}"></script>

	<script type="text/javascript">
		$(document).ready(function(){
            $('#loginform').validate({
                wrapper: "span",
                errorClass:"has-error",
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
                    email:{
                        required:true,
                        email: true
                    },
                    password:{
                        required:true,
                    }
                 },
                messages: {
                    email:{
                        required:"Please enter email.",
                        email: "Please enter valid email.",
                    },
                    password:{
                        required:"Please enter your password."
                    }
                },
                /*submitHandler: function(form) { 
                    $('.preloader').show();
                    form.submit();
                }*/
            });
        });
	</script>

</body>
</html>