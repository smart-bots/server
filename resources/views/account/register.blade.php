<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ trans('register.title') }} | Smartbots</title>
    <link rel="shortcut icon" href="assets/img/favicon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Lato:400,300,300italic,400italic,700,700italic">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/skin/default_skin/css/theme.css') }} ">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/allcp/forms/css/forms.css') }}">
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
        <section id="content" class="">
            <div class="allcp-form theme-primary mw600" id="register">
                <div class="bg-primary mw600 text-center mb20 br3 pv15">
                    <span class="logo"><span class="fa fa-signal"></span> <span class="lgf">Smart</span>bots</span>
                </div>
                <div class="panel panel-primary">
                    <div class="panel-heading pn">
                        <span class="panel-title">{{ trans('register.helper') }}</span>
                    </div>
                    <form method="post" action="/" id="form-register">
                        <div class="panel-body pn">
                            <div class="section">
                                <label for="username" class="field prepend-icon">
                                    <input type="text" name="username" id="username" class="gui-input" placeholder="{{ trans('register.username') }}">
                                    <label for="username" class="field-icon">
                                        <i class="fa fa-user"></i>
                                    </label>
                                </label>
                            </div>
                            <div class="section">
                                <label for="name" class="field prepend-icon">
                                    <input type="text" name="name" id="name" class="gui-input" placeholder="{{ trans('register.name') }}">
                                    <label for="name" class="field-icon">
                                        <i class="fa fa-font"></i>
                                    </label>
                                </label>
                            </div>
                            <div class="section">
                                <label for="email" class="field prepend-icon">
                                    <input type="email" name="email" id="email" class="gui-input" placeholder="{{ trans('register.email') }}">
                                    <label for="email" class="field-icon">
                                        <i class="fa fa-envelope"></i>
                                    </label>
                                </label>
                            </div>
                            <div class="section">
                                <label for="phone" class="field prepend-icon">
                                    <input type="phone" name="phone" id="phone" class="gui-input" placeholder="{{ trans('register.phone') }}">
                                    <label for="phone" class="field-icon">
                                        <i class="fa fa-phone"></i>
                                    </label>
                                </label>
                            </div>
                            <div class="section">
                                <label for="password" class="field prepend-icon">
                                    <input type="text" name="password" id="password" class="gui-input" placeholder="{{ trans('register.password') }}">
                                    <label for="password" class="field-icon">
                                        <i class="fa fa-lock"></i>
                                    </label>
                                </label>
                            </div>
                            <div class="section">
                                <label for="confirmPassword" class="field prepend-icon">
                                    <input type="text" name="confirmPassword" id="confirmPassword" class="gui-input" placeholder="{{ trans('register.password_confirmation') }}">
                                    <label for="confirmPassword" class="field-icon">
                                        <i class="fa fa-unlock-alt"></i>
                                    </label>
                                </label>
                            </div>
                            <div class="section">
                                <div class="bs-component pull-left mt10">
                                    <div class="checkbox-custom checkbox-primary mb5">
                                        <input type="checkbox" checked="" id="agree">
                                        <label for="agree">{{ trans('register.agree_to') }}
                                            <a href="#">  {{ trans('register.terms') }}</a></label>
                                    </div>
                                </div>
                                <div class="pull-right">
                                    <button type="submit" class="btn btn-bordered btn-primary">{{ trans('register.register') }}</button>
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-info mt20">
                            <strong><a href="{{ route('a::login') }}" class="text-center">{{ trans('register.link_login') }}</a><br/></strong>
                        </div>
                        <div class="alert alert-info mb5">
                            <strong><a href="{{ route('a::forgot') }}">{{ trans('register.link_forgot') }}</a></strong>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </section>
</div>
<script src="{{ asset('public/js/jquery/jquery-1.11.3.min.js') }} "></script>
<script src="{{ asset('public/js/jquery/jquery_ui/jquery-ui.min.js') }}"></script>
<script src="{{ asset('public/js/plugins/canvasbg/canvasbg.js') }}"></script>
<script src="{{ asset('public/js/utility/utility.js') }}"></script>
<script src="{{ asset('public/js/main.js') }}"></script>
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
</script>
</body>
</html>
