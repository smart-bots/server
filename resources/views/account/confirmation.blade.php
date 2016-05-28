<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ trans('confirmation.title') }} | Smartbots</title>
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
        <section id="content">
            <div class="center-block mt70" style="max-width: 450px">
                <div class="bg-success text-center mb20 br3 pv15">
                    <span class="logo"><span class="fa fa-signal"></span> <span class="lgf">Smart</span>bots</span>
                </div>
                <div class="panel panel-success mt15">
                    <div class="panel-heading pn">
                        <div class="panel-title">{{ trans('confirmation.helper') }}</div>
                    </div>
                    <div class="panel-body pn pt30 pbn">
                        <p class="dropcap dropcap-success">{!! trans('confirmation.info') !!}</p>
                        <p class="text-right mt20">
                            <button class="btn btn-success ph40" type="button">{{ trans('confirmation.login') }}</button>
                        </p>
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

