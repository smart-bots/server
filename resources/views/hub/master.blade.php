<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="ie=edge" http-equiv="x-ua-compatible">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="@asset('favicon.ico')" rel="shortcut icon">
    <title>
        @yield('title') | SmartBots
    </title>
    <meta content="noindex, nofollow" name="robots">
    <!-- no bots - no hurt -->
    <link href="@asset('public/libs/bootstrap/css/bootstrap.css')" rel="stylesheet" type="text/css"/>
    <link href="@asset('public/libs/font-awesome/css/font-awesome.css')" rel="stylesheet" type="text/css"/>
    <link href="@asset('public/libs/themify-icons/css/themify-icons.css')" rel="stylesheet" type="text/css"/>
    <link href="@asset('public/libs/animate.css/animate.css')" rel="stylesheet" type="text/css"/>
    <link href="@asset('public/libs/Waves/waves.css')" rel="stylesheet" type="text/css"/>
    <link href="@asset('public/libs/sweetalert/sweetalert.css')" rel="stylesheet" type="text/css"/>
    <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    @yield('additionHeader')

    <link href="@asset('public/css/core.css')" rel="stylesheet" type="text/css"/>
    <link href="@asset('public/css/components.css')" rel="stylesheet" type="text/css"/>
    <link href="@asset('public/css/libs.css')" rel="stylesheet" type="text/css"/>
    <link href="@asset('public/css/responsive.css')" rel="stylesheet" type="text/css"/>
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

<div class="languague-bar" style="position: fixed; bottom: 10px; right: 10px;">
    <a href="javascript:setLang('vi')"><img src="@asset('public/images/vi.png')" style="width: 30px"></a>
    <a href="javascript:setLang('en')"><img src="@asset('public/images/en.png')" style="width: 30px"></a>
</div>

<script>
    var resizefunc = [];
</script>
<script type="text/javascript" src="@asset('public/libs/jquery/jquery.js')"></script>
<script type="text/javascript" src="@asset('public/libs/raven-js/raven.js')"></script>
<script>Raven.config('https://fbc910700f1a485c9fc08c38fc8cbec1@app.getsentry.com/83304').install()</script>
<script type="text/javascript" src="@asset('public/libs/modernizr/modernizr.js')"></script>
<script type="text/javascript" src="@asset('public/libs/bootstrap/js/bootstrap.js')"></script>
<script type="text/javascript" src="@asset('public/libs/detectmobilebrowser/detectmobilebrowser.js')"></script>
<script type="text/javascript" src="@asset('public/libs/fastclick/fastclick.js')"></script>
<script type="text/javascript" src="@asset('public/libs/blockUI/jquery.blockUI.js')"></script>
<script type="text/javascript" src="@asset('public/libs/Waves/waves.js')"></script>
<script type="text/javascript" src="@asset('public/libs/wow/wow.js')"></script>
<script type="text/javascript" src="@asset('public/libs/slimscroll/jquery.slimscroll.js')"></script>
<script type="text/javascript" src="@asset('public/libs/jquery.nicescroll/jquery.nicescroll.js')"></script>
<script type="text/javascript" src="@asset('public/libs/jquery.scrollTo/jquery.scrollTo.js')"></script>
<script type="text/javascript" src="@asset('public/libs/sweetalert/sweetalert.js')"></script>
<script type="text/javascript" src="@asset('public/js/jquery.core.js')"></script>
<script type="text/javascript" src="@asset('public/js/jquery.app.js')"></script>
<script type="text/javascript" src="@asset('public/js/jquery.custom.js')"></script>
<script>
    function logout() {
        swal({
            title: '@trans('account/logout.title')',
            text: '@trans('account/logout.text')',
            type: "warning",
            showCancelButton: true,
            confirmButtonText: '@trans('account/logout.confirm')',
            cancelButtonText: '@trans('account/logout.cancel')',
            closeOnConfirm: false }, function() {
                $.ajax({
                    url : '@route('a::logout')',
                    type : 'get',
                    success : function (response)
                    {
                        window.location.href=response.href;
                    }
                });
            });
    }
</script>
@if (session()->has('currentHub'))
<script type="text/javascript" src="@asset('public/libs/socket.io/socket.io.js')"></script>
<script type="text/javascript" src="@asset('public/libs/tinycon/tinycon.js')"></script>
<script type="text/javascript" src="@asset('public/libs/notifyjs/js/notify.js')"></script>
<script type="text/javascript" src="@asset('public/libs/notifyjs/js/notify-metro.js')"></script>
<script type="text/javascript" src="@asset('public/libs/typeahead.js/typeahead.bundle.js')"></script>
<script type="text/javascript" src="@asset('public/libs/handlebars/handlebars.js')"></script>
<script>

    var socket = io('@env('APP_DOMAIN'):@env('SOCKETIO_PORT')@env('APP_NAMESPACE')');

    function hubDeactivate() {
        $.ajax({
            url : '@route('h::deactivate')',
            type : 'get',
            success : function (response)
            {
                hubStatus = $('#hubStatus').attr('onclick','hubReactivate()');
                hubStatus.find('i').removeClass('text-custom').addClass('text-danger');
                hubStatus.find('span').text('Deactivated');
                if (window.location.href == '@route('h::edit')') {
                    hubDeactivateBtn = $('#hubDeactivateBtn').attr('id','hubReactivateBtn').removeClass('btn-warning').addClass('btn-default').attr('onclick','hubReactivate()');
                    hubDeactivateBtn.find('i').removeClass('fa-ban').addClass('fa-check-square-o');
                    hubDeactivateBtn.find('span').text('Reactivate');
                }
            }
        });
    }

    function hubReactivate() {
        $.ajax({
            url : '@route('h::reactivate')',
            type : 'get',
            success : function (response)
            {
                hubStatus = $('#hubStatus').attr('onclick','hubDeactivate()');
                hubStatus.find('i').addClass('text-success').removeClass('text-custom');
                hubStatus.find('span').text('Activated');
                if (window.location.href == '@route('h::edit')') {
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
            title: '@trans('hub/hub.logout_title')',
            text: '@trans('hub/hub.logout_text')',
            type: "warning",
            showCancelButton: true,
            confirmButtonText: '@trans('hub/hub.logout_confirm')',
            cancelButtonText: '@trans('hub/hub.logout_cancel')',
            closeOnConfirm: false }, function() {
                $.ajax({
                    url : '@route('h::logout')',
                    type : 'get',
                    success : function (response)
                    {
                        window.location.href="@route('h::index')";
                    }
                });
            });
    }

    //-----------------------------------------------------------------------------------------------------------------

    var title = $(document).attr('title');

    var notiCounter = parseInt($('#noti-counter').text());

    function readNotify(id) {

        if ($('#noti' + id).exists()) {

            $.ajax({
                url : '@route('h::n::read')',
                type : 'get',
                dataType : 'json',
                data: { id: id }
            });

            $('#noti' + id).remove();

            notiCounter--;

            $('#noti-counter').text(notiCounter);

            if (notiCounter <= 0) {
                $(document).attr('title',title);
            } else {
                $(document).attr('title','('+notiCounter+') '+title);
            }

            Tinycon.setBubble(notiCounter);
        }

        return false;
    }

    if (notiCounter > 0) {
        $(document).attr('title','('+notiCounter+') '+title);
    }

    Tinycon.setBubble(notiCounter);

    var Notification = window.Notification || window.mozNotification || window.webkitNotification;

    if (Notification) {
        Notification.requestPermission();
    }

    socket.on('notification:new', function(message) { // New notification arrived

        console.log(message);

        var holder = message.holder.split(':');

        if (holder[0] == 'icon') {
            var holderData = '<em class="fa fa-' + holder[1] + ' fa-2x text-custom"></em>';
        } else if (holder[0] == 'image') {
            var holderData = '<img src="' + holder[1] + ':' + holder[2] + '" class="img-noti">';
        }

        $('#noti-list').prepend([
             '<a href="' + message.href + '" class="list-group-item" id="noti' + message.id + '" onclick="readNotify(' + message.id + ')">',
               '<div class="media">',
                  '<div class="pull-left p-r-10">',
                    holderData,
                  '</div>',
                  '<div class="media-body">',
                     '<h5 class="media-heading">' + message.subject + '</h5>',
                     '<p class="m-0">',
                         '<small>' + message.body + '</small>',
                     '</p>',
                  '</div>',
               '</div>',
            '</a>'
        ].join(''));

        $.Notification.notify('custom','bottom left', message.subject, message.body);

        if (Notification && Notification.permission === "granted") {

            var noti = new Notification(
                message.subject, {
                    icon: "@asset('logo-norounded.png')",
                    body: message.body
                }
            );

            noti.onclick = function () {
                readNotify(message.id);
                window.location.href = message.href;
                window.focus();
                noti.close()
            };

            setTimeout(function(){noti.close();},5000);
        }

        notiCounter++;

        $('#noti-counter').text(notiCounter);
        $(document).attr('title','('+notiCounter+') '+title);

        Tinycon.setBubble(notiCounter);
    });

    socket.on('notification:read', function(message) {

        console.log(message);

        readNotify(message.id);
    });

    //-----------------------------------------------------------------------------------------------------------------

    var bot = new Bloodhound({
      datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
      queryTokenizer: Bloodhound.tokenizers.whitespace,
      remote: {
        url: '@route('h::b::search')/%Q/@session('currentHub')',
        wildcard: '%Q'
      }
    });

    var typeahead_bot_option = {
      name: 'bot',
      display: 'id',
      source: bot,
      templates: {
        empty: [
        '<div class="tt-no-result">',
        'No result',
        '</div>'
        ].join(''),
        suggestion: Handlebars.compile([
          '<div class="">',
            '<div class="pull-left">',
                '<img src="@{{ image }}" alt="" class="user-mini-ava">',
            '</div>',
            '<div>',
                '<strong>@{{ name }}</strong>',
                '<p class="m-0">@{{ id }}</p>',
            '</div>',
          '</div>'].join(''))
      }
    };

    $('[name="quickbot"]').typeahead(null, typeahead_bot_option);

    function addQuick() {

        $.ajax({
            url : '@route('h::q::add')',
            type : 'get',
            dataType : 'json',
            data: { id: $('[name=quickbot]').val() },
            success: function (response) {
                $('#quick-modal').modal('hide');
                if (response.success == true) {
                    $('.quick-control').append([
                        '<li class="list-group-item" id="quick' + response.bot['id'] + '">',
                            '<a>',
                                '<a class="btn btn-danger btn-xs pull-left waves-effect waves-light quick-control-delete-btn" href="javascript:removeQuick(' + response.bot['id'] + ')" style="display: none">',
                                  '<em class="fa fa-trash" aria-hidden="true"></em>',
                                '</a>',
                            '</a>',
                            '<div class="avatar">',
                                '<img src="' + response.bot['image'] + '">',
                            '</div>',
                            '<span class="name">' + response.bot['name'] + '</span>',
                            '<div class="material-switch" style="position: absolute; right: 10px;">',
                                '<input id="bot' + response.bot['id'] + '" type="checkbox"/>',
                                '<label for="bot' + response.bot['id'] + '" class="label-default"></label>',
                            '</div>',
                            '<span class="clearfix"></span>',
                        '</li>'
                    ].join(''));
                }
            }
        });

        return false;
    }

    function removeQuick(id) {
        $.ajax({
            url : '@route('h::q::remove')',
            type : 'get',
            dataType : 'json',
            data: { id: id },
            success: function (response) {
                $('#quick' + id).remove();
            }
        });

        return false;
    }

    function toggleQuickRmBtn() {
        $('.quick-control-delete-btn').toggle();

        if ($('.quick-control-delete-btn').is(":visible")) {
            $('.contacts-list .list-group-item span.name').css('width', '105px');
        } else {
            $('.contacts-list .list-group-item span.name').css('width', '140px');
        }
    }

    //-----------------------------------------------------------------------------------------------------------------

</script>
@endif
@yield('additionFooter')
</body>
</html>
