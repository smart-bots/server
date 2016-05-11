<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Smart Switch</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="{{ asset('resources/assets/bootstrap/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="{{ asset('resources/assets/dist/css/AdminLTE.css') }}">
  <link rel="stylesheet" href="{{ asset('resources/assets/plugins/html5imageupload/html5imageupload.css') }}">
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
    <div class="login-box-body">
    <a href="{{ route('h::index') }}">{!! Form::button('<i class="fa fa-chevron-left" aria-hidden="true"></i>&nbsp;&nbsp;Back', ['type' => 'submit', 'class' => 'btn btn-primary pull-left btn-flat', 'style' => 'position:relative;top:-36px;']) !!}</a>
      <p class="login-box-msg" style="margin-bottom:14px;"></p>
      {!! Form::open(['route' => 'h::create','files' => true]) !!}
        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
          {!! Form::label('name', 'Name') !!}
          {!! Form::text('name', old('name'), ['class' => 'form-control']) !!}
          @if ($errors->has('name'))
            <span class="help-block">
                {{ $errors->first('name') }}
            </span>
          @endif
        </div>
        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
          {!! Form::label('description', 'Description') !!}
          {!! Form::textarea('description', old('description'), ['class' => 'form-control']) !!}
          @if ($errors->has('description'))
            <span class="help-block">
                {{ $errors->first('description') }}
            </span>
          @endif
        </div>
        <div class="form-group">
          {!! Form::label('image', 'Image') !!}
          <div class="dropzone image-dropzone" data-ghost="false" data-canvas="true" data-originalsize="false" data-ajax="false" data-width="200" data-height="200">
            {!! Form::file('image') !!}
          </div>
        </div>
        {!! Form::button('<i class="fa fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;Create', ['type' => 'submit', 'class' => 'btn btn-primary pull-right btn-flat']) !!}
      {!! Form::close() !!}
    </div>
  </div>
  <script src="{{ asset('resources/assets/plugins/jQuery/jQuery-2.2.0.min.js') }}"></script>
  <script src="{{ asset('resources/assets/bootstrap/js/bootstrap.min.js') }}"></script>
  <script src="{{ asset('resources/assets/plugins/html5imageupload/html5imageupload.js') }}"></script>
  <script>
    $('.dropzone').html5imageupload();
  </script>
</body>
</html>
