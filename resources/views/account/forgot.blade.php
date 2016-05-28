<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ trans('forgot.title') }} | Smartbots</title>
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
        <section id="content" class="animated fadeIn">
            <div class="allcp-form theme-warning mw500" style="margin-top: 10%;" id="login">
                <div class="bg-alert text-center mb20 br3 pv15">
                    <span class="logo"><span class="fa fa-signal"></span> <span class="lgf">Smart</span>bots</span>
                </div>
                <div class="panel panel-alert">
                    <div class="panel-heading pn">
                        <div class="panel-title">{{ trans('forgot.helper') }}</div>
                    </div>
                    <form method="post" action="/" id="contact">
                        <div class="panel-body pn pb10 pt25 mtn">
                            <p>{{ trans('forgot.mini_helper') }}</p>
                            <div class="section mn">
                                <div class="smart-widget sm-right smr-80">
                                    <label for="email" class="field prepend-icon">
                                        <input type="text" name="email" id="email" class="gui-input" placeholder="{{ trans('forgot.email') }}">
                                        <label for="email" class="field-icon text-alert">
                                            <i class="fa fa-envelope-o"></i>
                                        </label>
                                    </label>
                                    <label for="email" class="button btn-warning">{{ trans('forgot.reset') }}</label>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="alert alert-warning mt15">
                        <strong><a href="{{ route('a::login') }}">{{ trans('forgot.link_login') }}</a></strong>
                    </div>
                    <div class="alert alert-warning mb5">
                        <strong><a href="{{ route('a::register') }}" class="text-center">{{ trans('forgot.link_register') }}</a><br/></strong>
                    </div>
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

