<!DOCTYPE html>
<html>
<head>
  <base href="{{ Request::root() }}" />
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>@yield('title') | SmartBots</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="{{ asset('resources/assets/bootstrap/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('resources/assets/plugins/font-awesome/css/font-awesome.min.css') }}">
  <link rel="stylesheet" href="{{ asset('resources/assets/dist/css/AdminLTE.css') }}">
  <link rel="stylesheet" href="{{ asset('resources/assets/dist/css/skins/_all-skins.css') }}">
  <link rel="stylesheet" href="{{ asset('resources/assets/plugins/pace/pace.css') }}">
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  @yield('additionHeader')
</head>
<body class="hold-transition skin-blue layout-boxed sidebar-mini">
<div class="wrapper">
  <header class="main-header">
    <a href="#" class="logo">
      <span class="logo-mini"><b>S</b>B</span>
      <span class="logo-lg"><b>Smart</b>Bots</span>
    </a>
    <nav class="navbar navbar-static-top" role="navigation">
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          @include('partials.userMenu')
          <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <aside class="main-sidebar">
    <section class="sidebar">
      @include('partials.hubPanel')
      @include('partials.menu')
    </section>
  </aside>
  <div class="content-wrapper">
    @yield('body')
  </div>
  <aside class="control-sidebar control-sidebar-dark">
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
      <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
    </ul>
    <div class="tab-content">
      <div class="tab-pane" id="control-sidebar-home-tab">
        <h3 class="control-sidebar-heading">Recent Activity</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript::;">
              <i class="menu-icon fa fa-birthday-cake bg-red"></i>
              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>
                <p>Will be 23 on April 24th</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript::;">
              <i class="menu-icon fa fa-user bg-yellow"></i>
              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>
                <p>New phone +1(800)555-1234</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript::;">
              <i class="menu-icon fa fa-envelope-o bg-light-blue"></i>
              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>
                <p>nora@example.com</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript::;">
              <i class="menu-icon fa fa-file-code-o bg-green"></i>
              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>
                <p>Execution time 5 seconds</p>
              </div>
            </a>
          </li>
        </ul>
        <h3 class="control-sidebar-heading">Tasks Progress</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript::;">
              <h4 class="control-sidebar-subheading">
                Custom Template Design
                <span class="label label-danger pull-right">70%</span>
              </h4>
              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript::;">
              <h4 class="control-sidebar-subheading">
                Update Resume
                <span class="label label-success pull-right">95%</span>
              </h4>
              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-success" style="width: 95%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript::;">
              <h4 class="control-sidebar-subheading">
                Laravel Integration
                <span class="label label-warning pull-right">50%</span>
              </h4>
              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-warning" style="width: 50%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript::;">
              <h4 class="control-sidebar-subheading">
                Back End Framework
                <span class="label label-primary pull-right">68%</span>
              </h4>
              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-primary" style="width: 68%"></div>
              </div>
            </a>
          </li>
        </ul>
      </div>
      <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
      <div class="tab-pane" id="control-sidebar-settings-tab">
        <form method="post">
          <h3 class="control-sidebar-heading">General Settings</h3>
          <div class="form-group">
            <label class="control-sidebar-subheading">
              Report panel usage
              <input type="checkbox" class="pull-right" checked>
            </label>
            <p>
              Some information about this general settings option
            </p>
          </div>
          <!-- /.form-group -->
          <div class="form-group">
            <label class="control-sidebar-subheading">
              Allow mail redirect
              <input type="checkbox" class="pull-right" checked>
            </label>
            <p>
              Other sets of options are available
            </p>
          </div>
          <!-- /.form-group -->
          <div class="form-group">
            <label class="control-sidebar-subheading">
              Expose author name in posts
              <input type="checkbox" class="pull-right" checked>
            </label>
            <p>
              Allow the user to show his name in blog posts
            </p>
          </div>
          <!-- /.form-group -->
          <h3 class="control-sidebar-heading">Chat Settings</h3>
          <div class="form-group">
            <label class="control-sidebar-subheading">
              Show me as online
              <input type="checkbox" class="pull-right" checked>
            </label>
          </div>
          <!-- /.form-group -->
          <div class="form-group">
            <label class="control-sidebar-subheading">
              Turn off notifications
              <input type="checkbox" class="pull-right">
            </label>
          </div>
          <!-- /.form-group -->
          <div class="form-group">
            <label class="control-sidebar-subheading">
              Delete chat history
              <a href="javascript::;" class="text-red pull-right"><i class="fa fa-trash-o"></i></a>
            </label>
          </div>
        </form>
      </div>
    </div>
  </aside>
  <div class="control-sidebar-bg"></div>
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 1.0.a
    </div>
    <strong>Copyright &copy; 2016 <a href="#">SmartBots</a>.</strong> All rights reserved.
  </footer>
</div>
<script src="{{ asset('resources/assets/plugins/jQuery/jQuery-2.2.0.min.js') }}"></script>
<!-- <script src="{{ asset('resources/assets/plugins/jQuery/jquery-1.12.3.min.js') }}"></script> -->
<script src="{{ asset('resources/assets/plugins/jQuery-migrate/jquery-migrate.min.js') }}"></script>
<script src="{{ asset('resources/assets/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('resources/assets/plugins/bootbox/bootbox.min.js') }}"></script>
<script src="{{ asset('resources/assets/plugins/pace/pace.min.js') }}"></script>
<script src="{{ asset('resources/assets/plugins/slimScroll/jquery.slimscroll.min.js') }}"></script>
<script src="{{ asset('resources/assets/plugins/fastclick/fastclick.js') }}"></script>
<script src="{{ asset('resources/assets/plugins/scrollTo/jquery.scrollTo.min.js') }}"></script>
<script src="{{ asset('resources/assets/plugins/pulsate/jquery.pulsate.min.js') }}"></script>
<script src="{{ asset('resources/assets/dist/js/app.js') }}"></script>
<script src="{{ asset('resources/assets/dist/js/demo.js') }}"></script>
<script src="{{ asset('resources/assets/dist/js/core.js') }}"></script>
<script type="text/javascript">
  function hubDelete() {
    bootbox.confirm("R u sure?", function(result) {
      if (result == true) {
      $.ajax({
          url : '{{ route('h::destroy') }}',
          type : 'get',
          success : function (response)
          {
            window.location.href="{{ route('h::index') }}";
          }
        });
      }
    });
  }
  function hubDeactivate() {
    bootbox.confirm("R u sure?", function(result) {
      if (result == true) {
      $.ajax({
          url : '{{ route('h::deactivate') }}',
          type : 'get',
          success : function (response)
          {
            hubStatus = $('#hubStatus').attr('href','javascript:hubReactivate()');
            hubStatus.find('i').removeClass('text-success').addClass('text-danger');
            hubStatus.find('span').text('Deactivated');
            if (window.location.href == '{{ route('h::edit') }}') {
              hubDeactivateBtn = $('#hubDeactivateBtn').attr('id','hubReactivateBtn').removeClass('btn-warning').addClass('bg-olive').attr('onclick','hubReactivate()');
              hubDeactivateBtn.find('i').removeClass('fa-ban').addClass('fa-check-square-o');
              hubDeactivateBtn.find('span').text('Reactivate');
            }
          }
        });
      }
    });
  }
  function hubReactivate() {
    bootbox.confirm("R u sure?", function(result) {
      if (result == true) {
      $.ajax({
          url : '{{ route('h::reactivate') }}',
          type : 'get',
          success : function (response)
          {
            hubStatus = $('#hubStatus').attr('href','javascript:hubDeactivate()');
            hubStatus.find('i').addClass('text-success').removeClass('text-danger');
            hubStatus.find('span').text('Activated');
            if (window.location.href == '{{ route('h::edit') }}') {
              hubReactivateBtn = $('#hubReactivateBtn').attr('id','hubDeactivateBtn').addClass('btn-warning').removeClass('bg-olive').attr('onclick','hubDeactivate()');
              hubReactivateBtn.find('i').addClass('fa-ban').removeClass('fa-check-square-o');
              hubReactivateBtn.find('span').text('Deactivate');
            }
          }
        });
      }
    });
  }
  function hubLogout()
  {
    bootbox.confirm("R u sure?", function(result) {
      if (result == true) {
      $.ajax({
          url : '{{ route('h::logout') }}',
          type : 'get',
          success : function (response)
          {
            window.location.href="{{ route('h::index') }}";
          }
        });
      }
    });
  }
  function logout() {
    bootbox.confirm("R u sure?", function(result) {
      if (result == true) {
      $.ajax({
          url : '{{ route('a::logout') }}',
          type : 'get',
          dataType : 'json',
          success : function (response)
          {
            window.location.href=response.href;
          }
        });
      }
    });
  }
</script>
@yield('additionFooter')
</body>
</html>
