@extends('hub.master')
@section('title','Hub Bots')
@section('additionHeader')
<link rel="stylesheet" href="{{ asset('resources/assets/plugins/bootstrap-switch/bootstrap-switch.min.css') }}">
@endsection
@section('additionFooter')
<script src="{{ asset('resources/assets/plugins/bootstrap-switch/bootstrap-switch.min.js') }}"></script>
<script>
  $.fn.bootstrapSwitch.defaults.size = 'small';
  $(".bot").bootstrapSwitch();
  $('.bot').on('switchChange.bootstrapSwitch', function(event, state) {
    if (state == true) val = 1; else val = 0;
    control($(this).attr('id'),val);
  });

  function control(id,val) {
    $.ajax({
      url : '{{ route('h::b::control') }}',
      type : 'post',
      dataType : 'json',
      data : {
        _token: '{{ csrf_token() }}',
        id: id,
        val: val
      },
      success : function (response)
      {
        if (response.error == 0) {
          return true;
        } else { return false };
      }
    });
  }

  function botsUpdate() {
    $.ajax({
      url : '{{ route('h::botsStatus') }}',
      type : 'get',
      dataType : 'json',
      success : function (response)
      {
        $.each(response, function(index, value) {
          switch(value) {
            case -1:
              $("#"+index).bootstrapSwitch('disabled', true);
              break;
            case 0:
              $("#"+index).bootstrapSwitch('disabled', false);
              $("#"+index).bootstrapSwitch('state', false, true);
              break;
            case 1:
              $("#"+index).bootstrapSwitch('disabled', false);
              $("#"+index).bootstrapSwitch('state', true, true);
              break;
            case 2:
              $("#"+index).bootstrapSwitch('disabled', true);
              break;
          }
        });
      }
    });
  }
  botsUpdate();
  setInterval(botsUpdate, 1000);
</script>
@endsection
@section('body')
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Hub manage
    <small>Manage your hub's bots</small>
  </h1>
    {{breadcrumb([
    'Hub' => route('h::edit'),
    'Bot' => route('h::b::index'),
    'Index' => 'active'
  ])}}
</section>
<!-- Main content -->
<section class="content">
  <!-- Default box -->
  <div class="box">
    <div class="box-header">
      <h3 class="box-title">Bots list</h3>
      <a href='{{ route('h::b::create') }}'>{!! Form::button('<i class="fa fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;Add bot', ['type' => 'submit', 'class' => 'btn btn-success pull-right']) !!}</a>
    </div>
    <div class="box-body">
      <ul class="users-list clearfix users-list-big">
      @if (count($bots)>0)
        @foreach ($bots as $bot)
        <li>
           <a href="{{ route('h::b::edit',$bot['id'])}}"><img class="img-thumbnail" src="{{ asset($bot['image']) }}"></a>
          <a class="users-list-name" href="{{ route('h::b::edit',$bot['id'])}}">{{ $bot['name'] }}</a>
          <input type="checkbox" class="bot" id="{{ $bot['id'] }}" @if ($bot['status'] == 1) checked @endif @if ($bot['status'] == 2) disabled @endif>
        </li>
        @endforeach
      @else
        <p>No bot found.</p>
      @endif
      </ul>
    </div>
  </div>
</section>
<!-- /.content -->
@endsection
