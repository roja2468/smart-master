@extends('Admin.app')
@section('title')
Dashboard
@endsection
@section('style')
<style type="text/css">
    #error_tag{
        color: red;
    }
    h4{
        margin: 0 !important;
    }
</style>
@endsection
@section('content')
<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-8 col-8 align-self-center">
            <h3 class="text-themecolor">Dashboard</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </div>
        <div class="col-md-4 col-8 align-self-center">
            <!-- <h4 class="text-themecolor">Current Job Number : <span id="current_job_number">0</span></h4> -->
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <div class="row">
        <!-- Column -->
        <div class="col-lg-4 col-md-12">
            <div class="card bg-primary">
                <div class="card-body">
                    <a href="{{route('admin.topic.list')}}">
                        <div class="d-flex">
                            <div class="mr-3 align-self-center">
                                <h1 class="text-white"><i class="icon-cloud-download"></i></h1>
                            </div>
                            <div>
                                <h3 class="card-title text-white">Number of Topics</h3>
                            </div>
                        </div>
                        <div class="row my-2">
                            <div class="col-4 align-self-center">
                                <h2 class="font-weight-light text-white text-nowrap text-truncate">{{$Topic}}</h2>
                            </div>
                            <div class="col-8 pb-3 pt-2 text-right">
                                <div class="spark-count" style="height:65px"><canvas width="146" height="70" style="display: inline-block; width: 146px; height: 70px; vertical-align: top;"></canvas></div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-12">
            <div class="card bg-success">
                <div class="card-body">
                    <a href="{{route('admin.topic.video.list')}}">
                        <div class="d-flex">
                            <div class="mr-3 align-self-center">
                                <h1 class="text-white"><i class="icon-cloud-download"></i></h1>
                            </div>
                            <div>
                                <h3 class="card-title text-white">Number of Videos</h3>
                            </div>
                        </div>
                        <div class="row my-2">
                            <div class="col-4 align-self-center">
                                <h2 class="font-weight-light text-white text-nowrap text-truncate">{{$TopicVideo}}</h2>
                            </div>
                            <div class="col-8 pb-3 pt-2 text-right">
                                <div class="spark-count" style="height:65px"><canvas width="146" height="70" style="display: inline-block; width: 146px; height: 70px; vertical-align: top;"></canvas></div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-12">
            <div class="card bg-success">
                <div class="card-body">
                    <a href="{{route('admin.topic.document.list')}}">
                        <div class="d-flex">
                            <div class="mr-3 align-self-center">
                                <h1 class="text-white"><i class="icon-cloud-download"></i></h1>
                            </div>
                            <div>
                                <h3 class="card-title text-white">Number of Documents</h3>
                            </div>
                        </div>
                        <div class="row my-2">
                            <div class="col-4 align-self-center">
                                <h2 class="font-weight-light text-white text-nowrap text-truncate">{{$TopicDocument}}</h2>
                            </div>
                            <div class="col-8 pb-3 pt-2 text-right">
                                <div class="spark-count" style="height:65px"><canvas width="146" height="70" style="display: inline-block; width: 146px; height: 70px; vertical-align: top;"></canvas></div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8 col-md-12">
            <div class="card">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs profile-tab" role="tablist">
                    <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#home" role="tab" aria-selected="true"><span class="d-none d-md-block">Recent Comments</span><span class="d-block d-md-none"><i class="mdi mdi-bulletin-board"></i></span></a>
                    </li>
                    
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active" id="home" role="tabpanel">
                        <div class="card-body">
                            <div class="profiletimeline position-relative">
                                @forelse($RecentComment as $key => $Comment)
                                <div class="sl-item mt-2 mb-3">
                                    <div class="sl-left float-left mr-3"> <img src="{{asset('uploads/profile_pic')}}/{{($Comment->User) ? $Comment->User->profile_pic : ''}}" onerror=this.src="{{asset('No_image_available.png')}}" alt="user" class="rounded-circle"></div>
                                    <div class="sl-right">
                                        <div>
                                            <div class="d-md-flex">
                                                <h5 class="mb-0 font-weight-light">
                                                    <a href="javascript:void(0)" class="link">{{($Comment->User) ? $Comment->User->f_name : '-'}}</a>
                                                </h5>
                                                <span class="sl-date text-muted ml-1">{{ Carbon\Carbon::parse($Comment->created_at)->diffForHumans()}}</span>
                                            </div>
                                            @php
                                                $type = '-';
                                            @endphp
                                            @if($Comment->type == 1)
                                                @php
                                                    $type = 'Document';
                                                    $type_name = ($Comment->TopicDocument) ? $Comment->TopicDocument->title : '-';
                                                @endphp
                                            @else
                                                @php
                                                    $type = 'Video';
                                                    $type_name = ($Comment->TopicVideo) ? $Comment->TopicVideo->title : '-';
                                                @endphp
                                            @endif
                                            <p style="margin-bottom: 0px !important;"><b>{{$type}}</b> : {{$type_name}}</p>
                                            <p>{{$Comment->comment}}</p>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                @empty
                                @endforelse
                            </div>
                        </div>
                    </div>
                    <!--second tab-->
                    
                    
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-12">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Our Customers</h3>
                    <h6 class="card-subtitle">Different Devices Used to Visit</h6>
                    
                </div>
                <div class="card-body text-center border-top">
                    <ul class="list-inline mb-0">
                        <li class="list-inline-item px-2">
                            <a href="{{route('admin.user.list')}}?user-type=paid">
                                <h2 class="font-weight-light mb-0">Paid Users</h2>
                                <h2 class="font-weight-light text-info mb-0">{{$PaidUser}}</h2>
                            </a>
                        </li>
                        <li class="list-inline-item px-2">
                            <a href="{{route('admin.user.list')}}?user-type=free">
                                <h2 class="font-weight-light mb-0">Free Users</h2>
                                <h2 class="font-weight-light text-info mb-0">{{$FreeUser}}</h2>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
