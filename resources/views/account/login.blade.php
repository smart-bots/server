<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ trans('login.title') }} | Smartbots</title>
    <link rel="shortcut icon" href="assets/img/favicon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Lato:400,300,300italic,400italic,700,700italic">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/skin/default_skin/css/theme.css') }} ">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/allcp/forms/css/forms.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/js/sweetalert/sweetalert.css') }}">
    <!--[if lt IE 9]>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.2/html5shiv.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
    .logo {
        font-family: 'Lato';
        font-size: 40px;
        font-weight: 100;
    }
    .logo .lgf {
        font-weight: 700;
    }
    .alert a {
        color: white;
    }
    </style>
</head>
<body class="utility-page sb-l-c sb-r-c">
<div id="main" class="animated fadeIn">
    <section id="content_wrapper">
        <div id="canvas-wrapper">
            <canvas id="demo-canvas"></canvas>
        </div>
        <section id="content">
            <div class="allcp-form theme-primary mw450" id="login">
                <div class="bg-primary text-center mb20 br3 pv15">
                    <span class="logo"><span class="fa fa-signal"></span> <span class="lgf">Smart</span>bots</span>
                </div>
                <div class="panel panel-primary mw450">
                    <div class="panel-heading pn">
                        <span class="panel-title">{{ trans('login.helper') }}</span>
                    </div>
                    {!! Form::open(['route' => 'a::login', 'name' => 'login-form', 'onsubmit' => 'return login()']) !!}
                        <div class="panel-body pn mt20">
                            <div class="section">
                                <label for="username" class="field prepend-icon">
                                    <input type="text" name="username" id="username" class="gui-input" placeholder="{{ trans('login.username') }}">
                                    <label for="username" class="field-icon">
                                        <i class="fa fa-user"></i>
                                    </label>
                                </label>
                            </div>
                            <div class="section">
                                <label for="password" class="field prepend-icon">
                                    <input type="password" name="password" id="password" class="gui-input" placeholder="{{ trans('login.password') }}">
                                    <label for="password" class="field-icon">
                                        <i class="fa fa-lock"></i>
                                    </label>
                                </label>
                            </div>
                            <div class="section">
                                <div class="bs-component pull-left pt5">
                                    <div class="radio-custom radio-primary mb5 lh25">
                                        <input type="checkbox" id="remember" name="remember" checked>
                                        <label for="remember">{{ trans('login.remember_me') }}</label>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-bordered btn-primary pull-right"><i class="fa fa-sign-in mr10" aria-hidden="true"></i>{{ trans('login.login') }}</button>
                            </div>
                        </div>
                        <div class="alert alert-primary mt20">
                            <a href="{{ route('a::register') }}"><i class="fa fa-user-plus mr10" aria-hidden="true"></i>{{ trans('login.link_register') }}</a>                        </div>
                        <div class="alert alert-primary mb5">
                            <a href="{{ route('a::forgot') }}"><i class="fa fa-info-circle mr10" aria-hidden="true"></i>{{ trans('login.link_forgot') }}</a>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </section>
    </section>
</div>
<script src="{{ asset('public/js/jquery/jquery-2.2.4.min.js') }} "></script>
<script src="{{ asset('public/js/jquery/jquery_ui/jquery-ui.min.js') }}"></script>
<script src="{{ asset('public/js/plugins/canvasbg/canvasbg.js') }}"></script>
<script src="{{ asset('public/js/utility/utility.js') }}"></script>
<script src="{{ asset('public/js/main.js') }}"></script>
<script src="{{ asset('public/js/plugins/scrollTo/jquery.scrollTo.min.js') }}"></script>
<script src="{{ asset('public/js/sweetalert/sweetalert.min.js') }}"></script>
<script src="{{ asset('public/js/custom.js') }}"></script>
<script type="text/javascript">
    jQuery(document).ready(function () {
        "use strict";
        Core.init();
        // CanvasBG.init({
        //     Loc: {
        //         x: window.innerWidth / 5,
        //         y: window.innerHeight / 10
        //     }
        // });
    });
    function login() {
        $.ajax({
            url : '{{ route('a::login') }}',
            type : 'post',
            data : $('[name=login-form]').serializeArray(),
            dataType : 'json',
            success : function (response)
            {
                // for (var prop in response) {
                //     $('[name="'+prop+'"]').haz('error',response[prop]);
                // };
                $('[name=login-form]').validate(response, ['remember']);
            }
        });
        return false;
    }
</script>
</body>
</html>
