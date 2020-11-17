@extends('Admin.app')
@section('title')
Profile
@endsection
@section('style')
<style type="text/css">
	.invalid-feedback{
		display: block;
		margin-left: 15px;
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
            <h3 class="text-themecolor m-b-0 m-t-0">Profile</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Profile</li>
            </ol>
        </div>
    </div>
    @if(Session::has('message'))
    <div class="alert alert-{{ Session::get('class1') }}">
      {{ Session::get('message') }}
      	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
          	<span aria-hidden="true">&times;</span>
        </button>
    </div>
	    @if(Session::get('class') == 'success-password')
	    	<script type="text/javascript">
    			setTimeout(function () {
    			       document.getElementById('logout-form').submit();
    			    }, 2000);
	    	</script>
	    @endif
    @endif
    <form id="logout-form-password-change" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <!-- Row -->
    <div class="row">
        <!-- Column -->
        <div class="col-lg-4 col-xlg-3 col-md-5">
            <div class="card">
                <div class="card-body">
                    <center class="m-t-30"> <img src="{{asset('no-photo.png')}}" class="img-circle" width="165px" />
                        <h4 class="card-title m-t-10">{{Auth::user()->first_name}} {{Auth::user()->last_name}}</h4>
                        <h6 class="card-subtitle">{{Auth::user()->first_name}} {{Auth::user()->last_name}} User</h6>
                    </center>
                </div>
                <div><hr></div>
                <div class="card-body"> <small class="text-muted">Email address </small>
                    <h6>{{Auth::user()->email}}</h6> 
                    <small class="text-muted p-t-30 db">Phone</small>
                    <h6>{{(Auth::user()->phone_1 !='') ? Auth::user()->phone_1 : '-'}}</h6> 
                </div>
            </div>
        </div>
        <!-- Column -->
        <!-- Column -->
        <div class="col-lg-8 col-xlg-9 col-md-7">
            <div class="card">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs profile-tab" role="tablist">
                    <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#profile" role="tab">Profile</a> </li>
                    <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#settings" role="tab">Settings</a> </li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <!--second tab-->
                    <div class="tab-pane active" id="profile" role="tabpanel">
                        <div class="card-body">
                            <form class="form-horizontal form-material" method="post" action="{{route('admin.update.profile')}}">
                            	@csrf
                                <div class="form-group">
                                    <label class="col-md-12">First Name</label>
                                    <div class="col-md-12">
                                        <input type="text" value="{{Auth::user()->first_name}}" placeholder="First Name" name="first_name" id="first_name" class="form-control form-control-line">
                                    </div>
                                    @error('first_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('first_name') }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="example-email" class="col-md-12">Last Name</label>
                                    <div class="col-md-12">
                                        <input type="text" value="{{Auth::user()->last_name}}" placeholder="Last Name" class="form-control form-control-line" name="last_name" id="last_name">
                                    </div>
                                    @error('last_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('last_name') }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="example-email" class="col-md-12">Email</label>
                                    <div class="col-md-12">
                                        <input type="email" readonly="" placeholder="Email" value="{{Auth::user()->email}}" class="form-control form-control-line" name="email" id="email">
                                    </div>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-md-12">Phone No</label>
                                    <div class="col-md-12">
                                        <input type="text" placeholder="123 456 7890" value="{{Auth::user()->phone_1}}" class="form-control form-control-line" name="phone">
                                    </div>
                                    @error('phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('phone') }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <button class="btn btn-success">Update Profile</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="tab-pane" id="settings" role="tabpanel">
                        <div class="card-body">
                            <form class="form-horizontal form-material" action="{{route('admin.change.password')}}" method="post">
                            	@csrf
                            	<div class="form-group">
                            	    <label class="col-md-12">Current Password</label>
                            	    <div class="col-md-12">
                            	        <input type="password" name="old_password" class="form-control form-control-line">
                            	    </div>
                            	    @error('old_password')
                            	        <span class="invalid-feedback" role="alert">
                            	            <strong>{{ $errors->first('old_password') }}</strong>
                            	        </span>
                            	    @enderror
                            	</div>
                                <div class="form-group">
                                    <label class="col-md-12">Password</label>
                                    <div class="col-md-12">
                                        <input type="password" name="password" class="form-control form-control-line">
                                    </div>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Confirm Password</label>
                                    <div class="col-md-12">
                                        <input type="password" name="password_confirmation" class="form-control form-control-line">
                                    </div>
                                    @error('password_confirmation')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <button class="btn btn-success">Change Password</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column -->
    </div>
    <!-- Row -->
</div>
@endsection