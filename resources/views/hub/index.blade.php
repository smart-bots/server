<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Hub Login | SmartBots</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="{{ asset('resources/assets/bootstrap/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="{{ asset('resources/assets/dist/css/AdminLTE.css') }}">
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
    <p class="login-box-msg">
    @if (count($hubs) > 0)
    Choose your hub to start your session</p>
  		<ul class="users-list clearfix users-list-big">
      @foreach ($hubs as $hub)
        <li style="width: 33.33%">
          <a href='javascript:chooseHub({{ $hub['id'] }})'>
          <img class="img-thumbnail" src="{{ asset($hub['image']) }}">
          <span class="users-list-name">{{ $hub['name'] }}</span>
          <span class="users-list-date">{{ count($hub['bots']) }} bots</span>
          </a>
        </li>
      @endforeach
      </ul>
    @else
    Add your own hub or join some hubs first</p>
    @endif
		<a href="{{ route('h::create') }}">{!! Form::button('<i class="fa fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;New hub', ['class' => 'btn btn-success pull-right btn-flat']) !!}</a>
  </div>
</div>
<script src="{{ asset('resources/assets/plugins/jQuery/jQuery-2.2.0.min.js') }}"></script>
<script src="{{ asset('resources/assets/bootstrap/js/bootstrap.min.js') }}"></script>
<script>
function chooseHub(id)
{
  $.ajax({
    url : '{{ route('h::login') }}',
    type : 'post',
    dataType : 'json',
    data : {
      _token: '{{ csrf_token() }}',
      id: id
    },
    success : function (response)
    {
      if (response.error == 0) {
        window.location.href = '{{ route('h::dashboard') }}';
      };
    }
  });
};
</script>
</body>
</html>
