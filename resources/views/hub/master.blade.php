<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="ie=edge" http-equiv="x-ua-compatible">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="{{ asset('favicon.ico') }}" rel="shortcut icon">
    <title>
        @yield('title') | SmartBots
    </title>
    <meta content="noindex, nofollow" name="robots">
    <!-- no bots - no hurt -->
    <link href="{{ asset('public/libs/bootstrap/css/bootstrap.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('public/libs/font-awesome/css/font-awesome.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('public/libs/themify-icons/css/themify-icons.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('public/libs/animate.css/animate.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('public/libs/Waves/waves.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('public/libs/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css"/>
    <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    @yield('additionHeader')

    <link href="{{ asset('public/css/core.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('public/css/components.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('public/css/libs.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('public/css/responsive.css') }}" rel="stylesheet" type="text/css"/>
</head>
<body class="fixed-left">
    <div id="wrapper">
        <div class="topbar animated fadeInDown">
            @if (session()->has('currentHub'))
            <div class="topbar-left">
                <div class="text-center">
                    <a class="logo" href="#">
                        <i aria-hidden="true" class="fa fa-signal"></i>
                        <span class="hidden-xs">
                            SmartB<i aria-hidden="true" class="fa fa-dot-circle-o"></i>ts
                        </span>
                    </a>
                </div>
            </div>
            @endif
            <div class="navbar navbar-default" role="navigation">
                <div class="container">
                    @if (session()->has('currentHub'))
                    <div class="pull-left">
                        <button class="button-menu-mobile open-left">
                            <i aria-hidden="true" class="ti-menu"></i>
                        </button>
                        <span class="clearfix"></span>
                    </div>
                    @endif
                    <form class="navbar-left app-search pull-left hidden-xs" role="search">
                        <input class="form-control" placeholder="Search..." type="text">
                        <a href="#">
                            <i class="fa fa-search"></i>
                        </a>
                    </form>
                    <ul class="nav navbar-nav navbar-right pull-right">
                        @if (session()->has('currentHub'))
                            @include('hub.partials.notifications-menu')
                        @endif
                        <li class="hidden-xs">
                            <a class="waves-effect waves-light" id="btn-fullscreen">
                                <i aria-hidden="true" class="ti-fullscreen"></i>
                            </a>
                        </li>
                        @if (session()->has('currentHub'))
                            <li class="pull-left">
                                <a class="right-bar-toggle waves-effect waves-light">
                                    <i aria-hidden="true" class="ti-menu-alt"></i>
                                </a>
                            </li>
                        @endif
                        @include('hub.partials.user-menu')
                    </ul>
            </div>
        </div>
    </div>
    @if (session()->has('currentHub'))
        <div class="left side-menu">
            <div class="sidebar-inner slimscrollleft animated fadeInLeft">
                @include('hub.partials.hub-panel')
                @include('hub.partials.menu')
                <div class="clearfix"></div>
            </div>
        </div>
    @endif
    <div id='outter-page-content'>
    <div class="content-page animated fadeInUp" @if (!session()->has('currentHub')) style="margin-left: 0 !important;" @endif id="page-content">
        <div class="content">
            <div class="container">
                @yield('body')
            </div>
        </div>
    </div>
    </div>
    @if (session()->has('currentHub'))
        @include('hub.partials.right-side-bar')
    @endif
</div>
<script>
    var resizefunc = [];
</script>
<script src="{{ asset('public/libs/jquery/jquery.js') }}"></script>
<script src="{{ asset('public/libs/modernizr/modernizr.js') }}"></script>
<script src="{{ asset('public/libs/bootstrap/js/bootstrap.js') }}"></script>
<script src="{{ asset('public/libs/detectmobilebrowser/detectmobilebrowser.js') }}"></script>
<script src="{{ asset('public/libs/fastclick/fastclick.js') }}"></script>
<script src="{{ asset('public/libs/blockUI/jquery.blockUI.js') }}"></script>
<script src="{{ asset('public/libs/Waves/waves.js') }}"></script>
<script src="{{ asset('public/libs/wow/wow.js') }}"></script>
<script src="{{ asset('public/libs/slimscroll/jquery.slimscroll.js') }}"></script>
<script src="{{ asset('public/libs/jquery.nicescroll/jquery.nicescroll.js') }}"></script>
<script src="{{ asset('public/libs/jquery.scrollTo/jquery.scrollTo.js') }}"></script>
<script src="{{ asset('public/libs/sweetalert/sweetalert.js') }}"></script>
<script src="{{ asset('public/js/jquery.core.js') }}"></script>
<script src="{{ asset('public/js/jquery.app.js') }}"></script>
<script src="{{ asset('public/js/jquery.custom.js') }}"></script>
<script>
    Waves.init();

    function hubDeactivate() {
        $.ajax({
            url : '{{ route('h::deactivate') }}',
            type : 'get',
            success : function (response)
            {
                hubStatus = $('#hubStatus').attr('onclick','hubReactivate()');
                hubStatus.find('i').removeClass('text-custom').addClass('text-danger');
                hubStatus.find('span').text('Deactivated');
                if (window.location.href == '{{ route('h::edit') }}') {
                    hubDeactivateBtn = $('#hubDeactivateBtn').attr('id','hubReactivateBtn').removeClass('btn-warning').addClass('btn-default').attr('onclick','hubReactivate()');
                    hubDeactivateBtn.find('i').removeClass('fa-ban').addClass('fa-check-square-o');
                    hubDeactivateBtn.find('span').text('Reactivate');
                }
            }
        });
    }

    function hubReactivate() {
        $.ajax({
            url : '{{ route('h::reactivate') }}',
            type : 'get',
            success : function (response)
            {
                hubStatus = $('#hubStatus').attr('onclick','hubDeactivate()');
                hubStatus.find('i').addClass('text-success').removeClass('text-custom');
                hubStatus.find('span').text('Activated');
                if (window.location.href == '{{ route('h::edit') }}') {
                    hubReactivateBtn = $('#hubReactivateBtn').attr('id','hubDeactivateBtn').addClass('btn-warning').removeClass('btn-default').attr('onclick','hubDeactivate()');
                    hubReactivateBtn.find('i').addClass('fa-ban').removeClass('fa-check-square-o');
                    hubReactivateBtn.find('span').text('Deactivate');
                }
            }
        });
    }

    function hubLogout()
    {
        swal({
            title: "Are you sure?",
            text: "To log out your hub?",
            type: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes",
            closeOnConfirm: false }, function() {
                $.ajax({
                    url : '{{ route('h::logout') }}',
                    type : 'get',
                    success : function (response)
                    {
                        window.location.href="{{ route('h::index') }}";
                    }
                });
            });
    }

    function logout() {
        swal({
            title: "Are you sure?",
            text: "To log out your account?",
            type: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes",
            closeOnConfirm: false }, function() {
                $.ajax({
                    url : '{{ route('a::logout') }}',
                    type : 'get',
                    success : function (response)
                    {
                        window.location.href=response.href;
                    }
                });
            });
    }
</script>
@yield('additionFooter')
</body>
</html>
