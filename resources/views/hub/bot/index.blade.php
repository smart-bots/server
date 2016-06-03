@extends('hub.master')
@section('title','Hub bots')
@section('additionHeader')
@endsection
@section('additionFooter')
<script>
  $(".bot").materialSwitch('default','margin-top: 5px');

  $(".bot").change(function () {
    var bot = $(this),
        bot_id = $(this).attr('id');
        bot_state = bot.prop('checked');
    if (bot_state == true) {
      var bot_value = 1;
    } else {
      var bot_value = 0;
    }
    control(bot_id,bot_value);
  })

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
              $("#"+index).prop('disabled', true);
              break;
            case 0:
              $("#"+index).prop('disabled', false);
              $("#"+index).prop('checked', false);
              break;
            case 1:
              $("#"+index).prop('disabled', false);
              $("#"+index).prop('checked', true, true);
              break;
            case 2:
              $("#"+index).prop('disabled', true);
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
{!! content_header('Hub bots', [
    'Hub'=> '#',
    'Bots' => 'active'
]) !!}
<div class="row">
    <div class="col-sm-12">
        <div class="card-box">
          <a href='{{ route('h::b::create') }}'>
            {!! Form::button('<span class="btn-label"><i class="fa fa-plus" aria-hidden="true"></i></span>Add bot', ['type' => 'button', 'class' => 'btn btn-default waves-effect waves-light btn-create']) !!}
          </a>
          <h3 class="m-t-0 header-title"><b>Bots list</b></h3>
          @if (count($bots)>0)
            <ul class="hubs-list clearfix">
            @foreach ($bots as $bot)
              <li>
                <a href="{{ route('h::b::edit',$bot['id'])}}">
                  <img class="img-thumbnail" src="{{ asset($bot['image']) }}">
                  <span class="hubs-list-name" href="{{ route('h::b::edit',$bot['id'])}}">{{ $bot['name'] }}</span>
                  <center>
                  <input type="checkbox" class="bot" id="{{ $bot['id'] }}" @if ($bot['status'] == 1) checked @endif @if ($bot['status'] == 2) disabled @endif>
                  </center>
                </a>
              </li>
            @endforeach
            </ul>
          @else
            <p>No bot found.</p>
          @endif
        </div>
    </div>
</div>
@endsection
