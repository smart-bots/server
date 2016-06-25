<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="@asset('favicon.ico')" rel="shortcut icon">

        <title>@yield('title') | Smartbots</title>

        <meta content="noindex, nofollow" name="robots">

        <link href="@asset('public/libs/bootstrap/css/bootstrap.css')" rel="stylesheet" type="text/css" />
        <link href="@asset('public/libs/font-awesome/css/font-awesome.css')" rel="stylesheet" type="text/css" />
        <link href="@asset('public/css/core.css')" rel="stylesheet" type="text/css" />
        <link href="@asset('public/css/components.css')" rel="stylesheet" type="text/css" />
        <link href="@asset('public/css/libs.css')" rel="stylesheet" type="text/css" />
        <link href="@asset('public/css/responsive.css')" rel="stylesheet" type="text/css" />
        <link href="@asset('public/libs/animate.css/animate.css')" rel="stylesheet" type="text/css" />
        <link href="@asset('public/libs/Waves/waves.css')" rel="stylesheet" type="text/css" />
        <link href="@asset('public/libs/sweetalert/sweetalert.css')" rel="stylesheet" type="text/css" />

        <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

        <style>
            .smartbot {
                font-size: 45px;
                font-weight: 100;
            }
            .smart {
                font-weight: 700;
            }
        </style>

        @yield('additionHeader')
    </head>
    <body>

        <div class="account-pages"></div>
        <div class="clearfix"></div>

        <div class="wrapper-page animated fadeIn">

            @yield('body')

        </div>

        <script>
            var resizefunc = [];
        </script>

        <!-- jQuery  -->
        <script src="@asset('public/libs/jquery/jquery.js')"></script>
        <script src="@asset('public/libs/modernizr/modernizr.js')"></script>
        <script src="@asset('public/libs/bootstrap/js/bootstrap.js')"></script>
        <script src="@asset('public/libs/detectmobilebrowser/detectmobilebrowser.js')"></script>
        <script src="@asset('public/libs/fastclick/fastclick.js')"></script>
        <script src="@asset('public/libs/blockUI/jquery.blockUI.js')"></script>
        <script src="@asset('public/libs/Waves/waves.js')"></script>
        <script src="@asset('public/libs/wow/wow.js')"></script>
        <script src="@asset('public/libs/slimscroll/jquery.slimscroll.js')"></script>
        <script src="@asset('public/libs/jquery.nicescroll/jquery.nicescroll.js')"></script>
        <script src="@asset('public/libs/jquery.scrollTo/jquery.scrollTo.js')"></script>
        <script src="@asset('public/libs/sweetalert/sweetalert.js')"></script>
        <script src="@asset('public/js/jquery.core.js')"></script>
        <script src="@asset('public/js/jquery.app.js')"></script>
        <script src="@asset('public/js/jquery.custom.js')"></script>

        <script>
            Waves.init();

            $(window).bind('unload', function(){
                $('div.wrapper-page').removeClass('animated fadeIn').addClass('animated fadeOut');
            });
        </script>

        @yield('additionFooter')
    </body>
</html>
