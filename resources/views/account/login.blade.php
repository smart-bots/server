<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Login | SmartBots</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="{{ asset('resources/assets/bootstrap/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="{{ asset('resources/assets/dist/css/AdminLTE.css') }}">
  <link rel="stylesheet" href="{{ asset('resources/assets/plugins/iCheck/square/blue.css') }}">
  <link rel="stylesheet" href="{{ asset('resources/assets/plugins/bootstrap-select/css/bootstrap-select.min.css') }}">
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="#"><b>Smart</b>Bots</a>
  </div>

  @if ($errors->has('custom'))
    <div class="alert alert-danger alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>&nbsp;
      {{ $errors->first('custom') }}
    </div>
  @endif

  <div class="login-box-body">
    <p class="login-box-msg">Log in to start your session</p>

    {!! Form::open(array('route' => 'a::login')) !!}
      @if (old('loginWith') == 'email' || !empty(old('email')))
      <div class="form-group has-feedback{{ $errors->has('email') ? ' has-error' : '' }}">
        <div class="input-group">
          <div class="input-group-btn">
            <select name="loginWith" class="form-control border-right-none">
              <option value="username">Username</option>
              <option value="email" selected>Email</option>
            </select>
          </div>
          {!! Form::text('email', old('email'), ['class' => 'form-control border-left-none', 'placeholder' => 'Email']) !!}
        </div>
        <span class="glyphicon glyphicon-user form-control-feedback" style='z-index:3'></span>
        @if ($errors->has('email'))
            <span class="help-block">
                {{ $errors->first('email') }}
            </span>
        @endif
      </div>
      @else
      <div class="form-group has-feedback{{ $errors->has('username') ? ' has-error' : '' }}">
        <div class="input-group">
          <div class="input-group-btn">
            <select name="loginWith" class="form-control border-right-none">
              <option value="username" selected>Username</option>
              <option value="email">Email</option>
            </select>
          </div>
          {!! Form::text('username', old('username'), ['class' => 'form-control border-left-none', 'placeholder' => 'Username']) !!}
        </div>
        <span class="glyphicon glyphicon-user form-control-feedback" style='z-index:3'></span>
        @if ($errors->has('username'))
            <span class="help-block">
                {{ $errors->first('username') }}
            </span>
        @endif
      </div>
      @endif
      <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">
        {!! Form::password('password', ['class' => 'form-control', 'placeholder' => 'Password']) !!}
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        @if ($errors->has('password'))
            <span class="help-block">
                {{ $errors->first('password') }}
            </span>
        @endif
      </div>
      <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck">
            <label>
              {!! Form::checkbox('remember', 1, true) !!}&nbsp;&nbsp;Remember Me
            </label>
          </div>
        </div>
        <div class="col-xs-4">
          {!! Form::button('<i class="fa fa-sign-in" aria-hidden="true"></i>&nbsp;&nbsp;Login', ['type' => 'submit', 'class' => 'btn btn-primary btn-block btn-flat']) !!}
        </div>
      </div>

    {!! Form::close() !!}

    <div class="social-auth-links text-center">
      <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in using
        Facebook</a>
      <a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign in using
        Google</a>
    </div>

    <a href="{{ route('a::forgot') }}">I forgot my password</a><br/>
    <a href="{{ route('a::register') }}" class="text-center">Register a new membership</a>

  </div>
</div>
<script src="{{ asset('resources/assets/plugins/jQuery/jQuery-2.2.0.min.js') }}"></script>
<script src="{{ asset('resources/assets/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('resources/assets/plugins/iCheck/icheck.min.js') }}"></script>
<script src="{{ asset('resources/assets/plugins/bootstrap-select/js/bootstrap-select.min.js') }}"></script>
<script>
  $(function () {
    $('select').selectpicker();
    $("[name='loginWith']").change(function() {
      switch($("[name='loginWith']").val()) {
        case 'username':
          $("[name='email']").attr('name','username').attr('placeholder','Username').val('');
          break;
        case 'email':
          $("[name='username']").attr('name','email').attr('placeholder','Email').val('');
          break;
      }
    })
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue'
    });
  });
</script>
</body>
</html>
