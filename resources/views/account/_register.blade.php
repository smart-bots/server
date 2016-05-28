<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Registration | SmartBots</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="{{ asset('resources/assets/bootstrap/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="{{ asset('resources/assets/dist/css/AdminLTE.css') }}">
  <link rel="stylesheet" href="{{ asset('resources/assets/plugins/iCheck/square/blue.css') }}">
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition register-page">
<div class="register-box">
  <div class="register-logo">
    <a href="#"><b>Smart</b>Bots</a>
  </div>

  @if ($errors->has('agree_with_terms'))
    <div class="alert alert-danger alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>&nbsp;
      {{ $errors->first('agree_with_terms') }}
    </div>
  @endif

  @if (session('success') === true)
    <div class="alert alert-success alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <span class="glyphicon glyphicons-ok" aria-hidden="true"></span>&nbsp;
      Registration succeed.
    </div>
  @endif

  <div class="register-box-body">
    <p class="login-box-msg">Register a new membership</p>

    {!! Form::open(array('route' => 'a::register')) !!}
      <div class="form-group has-feedback{{ $errors->has('username') ? ' has-error' : '' }}">
        {!! Form::text('username', old('username'), ['class' => 'form-control', 'placeholder' => 'Username']) !!}
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
        @if ($errors->has('username'))
            <span class="help-block">
                {{ $errors->first('username') }}
            </span>
        @endif
      </div>
      <div class="form-group has-feedback{{ $errors->has('name') ? ' has-error' : '' }}">
        {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => 'Full name']) !!}
        <span class="glyphicon glyphicon-font form-control-feedback"></span>
        @if ($errors->has('name'))
            <span class="help-block">
                {{ $errors->first('name') }}
            </span>
        @endif
      </div>
      <div class="form-group has-feedback{{ $errors->has('email') ? ' has-error' : '' }}">
        {!! Form::text('email', old('email'), ['class' => 'form-control', 'placeholder' => 'Email']) !!}
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        @if ($errors->has('email'))
            <span class="help-block">
                {{ $errors->first('email') }}
            </span>
        @endif
      </div>
      <div class="form-group has-feedback{{ $errors->has('phone') ? ' has-error' : '' }}">
        {!! Form::text('phone', old('phone'), ['class' => 'form-control', 'placeholder' => 'Phone']) !!}
        <span class="glyphicon glyphicon-earphone form-control-feedback"></span>
        @if ($errors->has('phone'))
            <span class="help-block">
                {{ $errors->first('phone') }}
            </span>
        @endif
      </div>
      <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">
        {!! Form::password('password', ['class' => 'form-control', 'placeholder' => 'Password']) !!}
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        @if ($errors->has('password'))
            <span class="help-block">
                {{ $errors->first('password') }}
            </span>
        @endif
      </div>
      <div class="form-group has-feedback{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
        {!! Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => 'Retype password']) !!}
        <span class="glyphicon glyphicon-repeat form-control-feedback"></span>
        @if ($errors->has('password_confirmation'))
            <span class="help-block">
                {{ $errors->first('password_confirmation') }}
            </span>
        @endif
      </div>
      <div class="row">
        <div class="col-xs-7">
          <div class="checkbox icheck">
              {!! Form::checkbox('agree_with_terms', '1') !!}&nbsp;&nbsp;I agree to the <a href="#">terms</a>
          </div>
        </div>
        <div class="col-xs-5">
          {!! Form::button('<i class="fa fa-user-plus" aria-hidden="true"></i>&nbsp;&nbsp;Register', ['type' => 'submit', 'class' => 'btn btn-primary btn-block btn-flat']) !!}
        </div>
      </div>
    {!! Form::close() !!}

    <a href="{{ route('a::login') }}" class="text-center">I already have a membership</a><br/>
    <a href="{{ route('a::forgot') }}">I forgot my password</a><br>
  </div>
</div>
<script src="{{ asset('resources/assets/plugins/jQuery/jQuery-2.2.0.min.js') }}"></script>
<script src="{{ asset('resources/assets/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('resources/assets/plugins/iCheck/icheck.min.js') }}"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%'
    });
  });
</script>
</body>
</html>
