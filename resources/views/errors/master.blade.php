<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="@asset('favicon.ico')" rel="shortcut icon">
        <title>@yield('title') | SmartBots</title>
        <link href="@asset('public/libs/bootstrap/css/bootstrap.css')" rel="stylesheet" type="text/css"/>
        <link href="@asset('public/css/core.css')" rel="stylesheet" type="text/css"/>
        <link href="@asset('public/css/components.css')" rel="stylesheet" type="text/css"/>
        <style>
            .account-pages {
              background: url("@asset('/public/images/agsquare.png')");
              position: absolute;
              height: 100%;
              width: 100%;
            }
            .wrapper-page {
              margin: 5% auto;
              position: relative;
              width: 420px;
            }
            .wrapper-page .card-box {
              border: 1px solid rgba(54, 64, 74, 0.1);
            }
            .panel-pages {
              border-radius: 6px;
            }
            .panel-pages .panel-body {
              padding: 30px;
            }
            .panel-pages .panel-heading {
              -moz-border-radius: 6px 6px 0px 0px;
              -webkit-border-radius: 6px 6px 0px 0px;
              border-radius: 6px 6px 0px 0px;
              padding: 40px 20px;
              position: relative;
            }
            .panel-pages .panel-heading h3 {
              position: relative;
              z-index: 999;
            }
            .user-thumb {
              position: relative;
              z-index: 999;
            }
            .user-thumb img {
              height: 88px;
              margin: 0px auto;
              width: 88px;
            }
            .ex-page-content .text-error {
              color: #252932;
              font-size: 98px;
              font-weight: 700;
              line-height: 150px;
            }
            .ex-page-content .text-error i {
              font-size: 78px;
              padding: 0px 10px;
            }
        </style>
    </head>
    <body>
        <div class="account-pages"></div>
        <div class="clearfix"></div>
        <div class="wrapper-page">
            <div class="ex-page-content text-center">
                @yield('content')
            </div>
            <div class="ex-page-content text-center">
                <a class="btn btn-default waves-effect waves-light" href="javascript:history.back()"> Go back</a>
            </div>
        </div>
    </body>
</html>
