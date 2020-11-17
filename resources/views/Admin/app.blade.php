<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('assets/images/favicon.png')}}">
    <title>{{ config('app.name', 'Laravel') }} Admin | @yield('title')</title>
    <!-- Bootstrap Core CSS -->
    <link href="{{asset('assets/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <!-- chartist CSS -->
    <link href="{{asset('assets/plugins/chartist-js/dist/chartist.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/plugins/chartist-js/dist/chartist-init.css')}}" rel="stylesheet">
    <link href="{{asset('assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.css')}}" rel="stylesheet">
    <link href="{{asset('assets/plugins/css-chart/css-chart.css')}}" rel="stylesheet">
    <!--This page css - Morris CSS -->
    <link href="{{asset('assets/plugins/c3-master/c3.min.css')}}" rel="stylesheet">
    <!-- Vector CSS -->
    <link href="{{asset('assets/plugins/vectormap/jquery-jvectormap-2.0.2.css')}}" rel="stylesheet" />
    <!-- Custom CSS -->
    <link href="{{asset('assets/css/style.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('assets/css/custom.css')}}">
    <!-- You can change the theme colors from here -->
    <link href="{{asset('assets/css/colors/blue.css')}}" id="theme" rel="stylesheet">
    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    @yield('style')
    <script type="text/javascript">
        var baseUrl = '{{ url("/admin") }}/';
        window.Laravel = <?php echo json_encode(['csrfToken' => csrf_token()]); ?>
    </script>
    <style type="text/css">
        .dataTables_empty{
            text-align: center !important;
        }
        .slimScrollDiv .message-center .slimScrollBar{
            height: 100% !important;
        }
        .swal2-confirm,.swal2-cancel{
            /*background-image:linear-gradient(to right, #720A85 , #B7334C) !important;*/
            background-image:rgb(48, 133, 214) !important;
        }
    </style>
</head>

<body class="fix-header fix-sidebar card-no-border">
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <header class="topbar">
            <nav class="navbar top-navbar navbar-expand-md navbar-light">
                <!-- ============================================================== -->
                <!-- Logo -->
                <!-- ============================================================== -->
                <div class="navbar-header" >
                    <a class="navbar-brand" href="{{route('admin.home')}}" style="color: white !important;">
                        <!-- Logo text -->
                        <span>
                            <img src="{{asset('no-photo.png')}}" width="150px" alt="homepage" class="light-logo" />
                        </span>
                         <!-- <b>UPSC 360</b> -->
                    </a>
                </div>
                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->
                <div class="navbar-collapse">
                    <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav mr-auto mt-md-0">
                        <li class="nav-item"> <a class="nav-link nav-toggler hidden-md-up text-muted waves-effect waves-dark" href="javascript:void(0)"><i class="mdi mdi-menu"></i></a> </li>
                        <li class="nav-item"> <a class="nav-link sidebartoggler hidden-sm-down text-muted waves-effect waves-dark" href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
                    </ul>
                    <!-- ============================================================== -->
                    <!-- User profile and search -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav my-lg-0">
                        <!-- ============================================================== -->
                        <!-- Comment -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="{{asset('no-photo.png')}}" alt="user" class="profile-pic" /></a>
                            <div class="dropdown-menu dropdown-menu-right scale-up">
                                <ul class="dropdown-user">
                                    <li>
                                        <div class="dw-user-box">
                                            <div class="u-text">
                                                <h4>{{Auth::user()->first_name}} {{Auth::user()->last_name}}</h4>
                                                <p class="text-muted">{{Auth::user()->email}}</p></div>
                                        </div>
                                    </li>
                                    <!-- <li role="separator" class="divider"></li>
                                    <li><a href="{{ route('admin.profile') }}"><i class="ti-user"></i> My Profile</a></li> -->
                                    <li role="separator" class="divider"></li>
                                    <li>
                                        <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i class="fa fa-power-off"></i> {{ __('Logout') }}</a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <!-- ============================================================== -->
                    </ul>
                </div>
            </nav>
        </header>
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- User profile -->
                <div class="user-profile" style="background: url('{{asset('assets/images/background/user-info.jpg')}}') no-repeat;">
                    <!-- User profile image -->
                    <div class="profile-img"> <img src="{{asset('icon.png')}}" alt="user" /> </div>
                    <!-- User profile text-->
                    <div class="profile-text"> <a href="#" class="dropdown-toggle u-dropdown" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">{{Auth::user()->first_name}} {{Auth::user()->last_name}}</a>
                        <div class="dropdown-menu animated flipInY">
                            <a href="{{route('admin.profile')}}" class="dropdown-item"><i class="ti-user"></i> My Profile</a>
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="dropdown-item"><i class="fa fa-power-off"></i> {{ __('Logout') }}</a>
                        </div>
                    </div>
                </div>
                <!-- End User profile text-->
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">

                        <li class="{{ Request::is('admin/dashboard') ? 'active' : '' }}"> 
                            <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                            <i class="fas fa-home"></i>
                            <span class="hide-menu">Home</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a class="{{ Request::is('admin/dashboard') ? 'active' : '' }}" href="{{route('admin.home')}}"><i class="mdi mdi-gauge m-r-10"></i>&nbsp;Dashboard</a></li>
                            </ul>
                        </li>

                        <li class="{{ (Request::is('admin/category/add') || Request::is('admin/category') || Request::is('admin/category/edit*') || Request::is('admin/sub-categories/add') || Request::is('admin/sub-categories') || Request::is('admin/sub-categories/edit*') || Request::is('admin/child-categories/add') || Request::is('admin/child-categories') || Request::is('admin/child-categories/edit*') ) ? 'active' : '' }}"> 
                            <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                            <i class="fas fa-angle-double-right"></i>
                            <span class="hide-menu">Category</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a class="{{ (Request::is('admin/category/add') || Request::is('admin/category') || Request::is('admin/category/edit*') ) ? 'active' : '' }}" href="{{route('admin.category.list')}}"><i class="fas fa-angle-double-right m-r-10" aria-hidden="true"></i>Category</a></li>
                                <li><a class="{{ (Request::is('admin/sub-categories') || Request::is('admin/sub-categories/add') || Request::is('admin/sub-categories/edit*') ) ? 'active' : '' }}" href="{{route('admin.sub_categories.list')}}"><i class="fas fa-angle-double-right m-r-10" aria-hidden="true"></i>Sub Category</a></li>
                                <li><a class="{{ (Request::is('admin/child-categories/add') || Request::is('admin/child-categories') || Request::is('admin/child-categories/edit*') ) ? 'active' : '' }}" href="{{route('admin.child_categories.list')}}"><i class="fas fa-angle-double-right m-r-10" aria-hidden="true"></i>Child Category</a></li>
                            </ul>
                        </li>
                        <li class="{{ (Request::is('admin/topic/add') || Request::is('admin/topic') || Request::is('admin/topic/edit*') || Request::is('admin/topic-video/add') || Request::is('admin/topic-video') || Request::is('admin/topic-video/edit*') || Request::is('admin/topic-document/add') || Request::is('admin/topic-document') || Request::is('admin/topic-document/edit*') ) ? 'active' : '' }}"> 
                            <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                            <i class="fas fa-list"></i>
                            <span class="hide-menu">Topic</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="{{route('admin.topic.list')}}" class="{{ (Request::is('admin/topic/add') || Request::is('admin/topic') || Request::is('admin/topic/edit*') ) ? 'active' : '' }}"><i class="fas fa-list m-r-10" aria-hidden="true"></i>Topic</a></li>
                                <li><a href="{{route('admin.topic.video.list')}}" class="{{ (Request::is('admin/topic-video/add') || Request::is('admin/topic-video') || Request::is('admin/topic-video/edit*') ) ? 'active' : '' }}"><i class="fas fa-film m-r-10" aria-hidden="true"></i>Topic Video</a></li>
                                <li><a href="{{route('admin.topic.document.list')}}" class="{{ (Request::is('admin/topic-document/add') || Request::is('admin/topic-document') || Request::is('admin/topic-document/edit*') ) ? 'active' : '' }}"><i class="fas fa-file-alt m-r-10" aria-hidden="true"></i>Topic Document</a></li>
                            </ul>
                        </li>
                        <li class="{{ (Request::is('admin/users') || Request::is('admin/user-packages')) ? 'active' : '' }}"> 
                            <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                            <i class="fas fa-user"></i>
                            <span class="hide-menu">Users</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a class="{{ Request::is('admin/users') ? 'active' : '' }}" href="{{route('admin.user.list')}}"><i class="fas fa-user m-r-10"></i>&nbsp;Users</a></li>
                                <li><a class="{{ Request::is('admin/user-packages') ? 'active' : '' }}" href="{{route('admin.user.package.list')}}"><i class="far fa-calendar-minus m-r-10"></i>&nbsp;Users Packages</a></li>
                            </ul>
                        </li>
                        <li class="{{ Request::is('admin/comments') ? 'active' : '' }}"> 
                            <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                            <i class="fas fa-comment-dots"></i>
                            <span class="hide-menu">Comment</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a class="{{ Request::is('admin/comments') ? 'active' : '' }}" href="{{route('admin.comment.list')}}"><i class="fas fa-comment-dots m-r-10"></i>&nbsp;Comment</a></li>
                            </ul>
                        </li>
                        <li class="{{ (Request::is('admin/slider') || Request::is('admin/slider/add') || Request::is('admin/slider/edit*') || Request::is('admin/contact') || Request::is('admin/about-us') || Request::is('admin/about-us/about-us-edit*') || Request::is('admin/faq') || Request::is('admin/faq/add') || Request::is('admin/faq/edit*') || Request::is('admin/app-notification') || Request::is('admin/app-notification/add') || Request::is('admin/app-notification/edit*') || Request::is('admin/privacy-policy') || Request::is('admin/privacy-policy/edit*')) ? 'active' : '' }}"> 
                            <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                            <i class="fas fa-newspaper"></i>
                            <span class="hide-menu">Content</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a class="{{ (Request::is('admin/slider') || Request::is('admin/slider/add') || Request::is('admin/slider/edit*')) ? 'active' : '' }}" href="{{route('admin.slider.list')}}"><i class="fas fa-newspaper m-r-10"></i>&nbsp;Slider</a></li>
                                <li><a class="{{ Request::is('admin/contact') ? 'active' : '' }}" href="{{route('admin.contact.list')}}"><i class="fas fa-newspaper m-r-10"></i>&nbsp;Contact Message</a></li>
                                <li><a class="{{ (Request::is('admin/about-us') || Request::is('admin/about-us/about-us-edit*')) ? 'active' : '' }}" href="{{route('admin.about_us.list')}}"><i class="fas fa-newspaper m-r-10"></i>&nbsp;About Us</a></li>
                                <li><a class="{{ (Request::is('admin/privacy-policy') || Request::is('admin/privacy-policy/edit*')) ? 'active' : '' }}" href="{{route('admin.privacy_policy.list')}}"><i class="fas fa-newspaper m-r-10"></i>&nbsp;Privacy & Policy</a></li>
                                <li><a class="{{ (Request::is('admin/faq') || Request::is('admin/faq/add') || Request::is('admin/faq/edit*')) ? 'active' : '' }}" href="{{route('admin.faq.list')}}"><i class="fas fa-question  m-r-10"></i>&nbsp;Faq</a></li>
                                <li><a class="{{ (Request::is('admin/app-notification') || Request::is('admin/app-notification/add') || Request::is('admin/app-notification/edit*')) ? 'active' : '' }}" href="{{route('admin.app_notification.list')}}"><i class="fas fa-comment  m-r-10"></i>&nbsp;App Notification</a></li>
                            </ul>
                        </li>
                        <li class="{{ (Request::is('admin/notification') || Request::is('admin/mail-notification')) ? 'active' : '' }}"> 
                            <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                            <i class="fas fa-bell"></i>
                            <span class="hide-menu">Notification</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a class="{{ Request::is('admin/notification') ? 'active' : '' }}" href="{{route('admin.notification.addPushNotification')}}"><i class="fas fa-bell m-r-10"></i>&nbsp;Push Notification</a></li>
                                <li><a class="{{ Request::is('admin/mail-notification') ? 'active' : '' }}" href="{{route('admin.mail_notification.addPushNotification')}}"><i class="fas fa-envelope m-r-10"></i>&nbsp;Mail Notification</a></li>
                            </ul>
                        </li>
                        <li class="{{ (Request::is('admin/package') || Request::is('admin/package/add') || Request::is('admin/package/edit*') || Request::is('admin/demo-video') || Request::is('admin/demo-video/add') || Request::is('admin/demo-video/edit*') || Request::is('admin/demo-article') || Request::is('admin/demo-article/add') || Request::is('admin/demo-article/edit*')) ? 'active' : '' }}"> 
                            <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                            <i class="fas fa-clipboard-list"></i>
                            <span class="hide-menu">Pakages</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a class="{{ (Request::is('admin/package') || Request::is('admin/package/add') || Request::is('admin/package/edit*')) ? 'active' : '' }}" href="{{route('admin.package.list')}}"><i class="fas fa-clipboard-list m-r-10"></i>&nbsp;Pakages</a></li>
                                <li><a class="{{ (Request::is('admin/demo-video') || Request::is('admin/demo-video/add') || Request::is('admin/demo-video/edit*')) ? 'active' : '' }}" href="{{route('admin.demo.video.list')}}"><i class="fas fa-film m-r-10"></i>&nbsp;Demo Videos</a></li>
                                <!-- <li><a class="{{ (Request::is('admin/demo-article') || Request::is('admin/demo-article/add') || Request::is('admin/demo-article/edit*')) ? 'active' : '' }}" href="{{route('admin.demo.article.list')}}"><i class="fas fa-clipboard-check m-r-10"></i>&nbsp;Demo articles</a></li> -->
                            </ul>
                        </li>
                        <li class="{{ (Request::is('admin/wallet') || Request::is('admin/referral-amount')) ? 'active' : '' }}"> 
                            <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                            <i class="far fa-money-bill-alt"></i>
                            <span class="hide-menu">Wallet</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a class="{{ Request::is('admin/wallet') ? 'active' : '' }}" href="{{route('admin.wallet.list')}}"><i class="far fa-money-bill-alt m-r-10"></i>&nbsp;Wallet Withdraw</a></li>
                                <li><a class="{{ Request::is('admin/referral-amount') ? 'active' : '' }}" href="{{route('admin.referral_amount.edit')}}"><i class="far fa-money-bill-alt m-r-10"></i>&nbsp;Referral Amount</a></li>
                                <li><a class="{{ Request::is('admin/referral-amount-commission') ? 'active' : '' }}" href="{{route('admin.referral_amount_commission.edit')}}"><i class="far fa-money-bill-alt m-r-10"></i>&nbsp;Referral Amount<br>Commission</a></li>
                            </ul>
                        </li>
                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
            <!-- Bottom points-->
            <div class="sidebar-footer">
                <!-- item-->
                <a href="{{route('admin.profile')}}" class="link" data-toggle="tooltip" title="Settings"><i class="ti-settings"></i></a>
                <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="link" data-toggle="tooltip" title="Logout"><i class="mdi mdi-power"></i></a>
            </div>
            <!-- End Bottom points-->
        </aside>
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            @yield('content')
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->
            <footer class="footer">
                © {{date('Y')}} {{ config('app.name', 'Laravel') }} Admin Panel.
            </footer>
            <!-- ============================================================== -->
            <!-- End footer -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="{{asset('assets/plugins/jquery/jquery.min.js')}}"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="{{asset('assets/plugins/popper/popper.min.js')}}"></script>
    <script src="{{asset('assets/plugins/bootstrap/js/bootstrap.min.js')}}"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="{{asset('assets/js/jquery.slimscroll.js')}}"></script>
    <!--Wave Effects -->
    <script src="{{asset('assets/js/waves.js')}}"></script>
    <!--Menu sidebar -->
    <script src="{{asset('assets/js/sidebarmenu.js')}}"></script>
    <!--stickey kit -->
    <script src="{{asset('assets/plugins/sticky-kit-master/dist/sticky-kit.min.js')}}"></script>
    <script src="{{asset('assets/plugins/sparkline/jquery.sparkline.min.js')}}"></script>
    <!--stickey kit -->
    <script src="{{asset('assets/plugins/sticky-kit-master/dist/sticky-kit.min.js')}}"></script>
    <script src="{{asset('assets/plugins/sparkline/jquery.sparkline.min.js')}}"></script>
    <script src="{{asset('assets/plugins/sparkline/jquery.sparkline.min.js')}}"></script>
    <!--Custom JavaScript -->
    <script src="{{asset('assets/js/custom.min.js')}}"></script>
    <script src="{{asset('assets/plugins/chartist-js/dist/chartist.min.js')}}"></script>
    <!--c3 JavaScript -->
    <script src="{{asset('assets/plugins/d3/d3.min.js')}}"></script>
    <script src="{{asset('assets/plugins/c3-master/c3.min.js')}}"></script>
    <!-- Vector map JavaScript -->
    <!-- <script src="{{asset('assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js')}}"></script>
    <script src="{{asset('assets/plugins/vectormap/jquery-jvectormap-us-aea-en.js')}}"></script> -->
    <script type="text/javascript" src="{{ asset('assets/plugins/sweetalert/sweetalert.min.js') }}"></script>
    @yield('script')
    <script type="text/javascript">
        $( document ).ajaxComplete(function() {
            $('[data-toggle="tooltip"]').tooltip({
                "html": true,
                "delay": {"show": 100, "hide": 0},
            });
        });
        $(".select2-container").tooltip({
            title: function() {
                return $(this).prev().attr("title");
            },
            placement: "bottom",
        });
    </script>
    @if(Session::has('success'))
    <script type="text/javascript">
        const Toast = Swal.mixin({
          toast: true,
          position: 'top-end',
          showConfirmButton: false,
          timer: 3000
        })
        Toast.fire({
          type: 'success',
          title: '{!! Session::get("success") !!}'
        })
    </script>
    @endif
    @if(Session::has('danger'))
    <script type="text/javascript">
        const Toast = Swal.mixin({
          toast: true,
          position: 'top-end',
          showConfirmButton: false,
          timer: 3000
        })
        Toast.fire({
          type: 'error',
          title: '{!! Session::get("danger") !!}'
        })
    </script>
    @endif
    <script>
        function restrictAlphabets(e) {
            var x = e.which || e.keycode;
            if ((x >= 48 && x <= 57))
                return true;
            else
                return false;
        }
        $(function() {
            $('select[name="parent_category"]').on('change', function() {
                var parentId = $(this).val();
                if(parentId) {
                    var url = '{{route("admin.child_categories.get_sub_category")}}';
                    $.ajax({
                        url: url,
                        type: "POST",
                        headers: {'X-CSRF-Token': Laravel.csrfToken},
                        data:{parentId:parentId},
                        dataType: "json",
                        success:function(data) {
                            $('select[name="sub_category"]').html('<option value="">-select sub category-</option>');
                            if(data.status == true)
                            {
                                $('select[name="sub_category"]').append(data.html);
                            }
                        }
                    });
                }else{
                    $('select[name="sub_category"]').html('<option value="">-select sub category-</option>');
                }
            });
            $('select[name="sub_category"]').on('change', function() {
                var subParentId = $(this).val();
                var parentId = $('select[name="parent_category"]').val();
                if(parentId && subParentId) {
                    var url = '{{route("admin.child_categories.get_child_category")}}';
                    $.ajax({
                        url: url,
                        type: "POST",
                        headers: {'X-CSRF-Token': Laravel.csrfToken},
                        data:{parentId:parentId,subParentId:subParentId},
                        dataType: "json",
                        success:function(data) {
                            $('select[name="child_category"]').html('<option value="">-select child category-</option>');
                            if(data.status == true)
                            {
                                $('select[name="child_category"]').append(data.html);
                            }
                        }
                    });
                }else{
                    $('select[name="child_category"]').html('<option value="">-select child category-</option>');
                }
            });
        });
    </script>
</body>
</html>