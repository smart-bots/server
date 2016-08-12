<?php
  use SmartBots\Hub;
?>
@extends('hub.master')
@section('title',trans('hub/bot.index'))
@section('additionHeader')
@endsection
@section('additionFooter')
<script>
  $(".bot").materialSwitch('default','margin-top: 5px');

  $(".bot").change(function () {
    var bot = $(this),
        bot_id = $(this).attr('id').replace('bot', '');
        bot_state = bot.prop('checked');
    if (bot_state == true) {
      var bot_value = 1;
    } else {
      var bot_value = 0;
    }
    control(bot_id,bot_value);
  });

  function control(id,val) {
    $.ajax({
      url : '@route('h::b::control')',
      type : 'get',
      dataType : 'json',
      data : {
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

  socket.on('bot:change', function(message) {

    console.log(message);

    var state = message.state;
    var id = message.id;

    switch (message.state) {
      case '-1':
        alert(message.id);
        $("#bot"+message.id).prop('disabled', true);
      break;
      case '0':
        $("#bot"+message.id).prop('disabled', false);
        $("#bot"+message.id).prop('checked', false);
      break;
      case '1':
        $("#bot"+message.id).prop('disabled', false);
        $("#bot"+message.id).prop('checked', true, true);
      break;
      case '2':
        $("#bot"+message.id).prop('disabled', true);
      break;
      case -1:
        alert(message.id);
        $("#bot"+message.id).prop('disabled', true);
      break;
      case 0:
        $("#bot"+message.id).prop('disabled', false);
        $("#bot"+message.id).prop('checked', false);
      break;
      case 1:
        $("#bot"+message.id).prop('disabled', false);
        $("#bot"+message.id).prop('checked', true, true);
      break;
      case 2:
        $("#bot"+message.id).prop('disabled', true);
      break;
    }

  });

</script>
@endsection
@section('body')
@header('Hub bots', [
    'Hub'=> '#',
    'Bots' => 'active'
])
<div class="row">
    <div class="col-sm-12">
        <div class="card-box">
          @if(auth()->user()->can('addBots',Hub::findOrFail(session('currentHub'))))
          <a href='@route('h::b::create')'>
            {!! Form::button('<span class="btn-label"><i class="fa fa-plus" aria-hidden="true"></i></span>Add bot', ['type' => 'button', 'class' => 'btn btn-default waves-effect waves-light btn-create']) !!}
          </a>
          @endif
          <h3 class="m-t-0 header-title"><b>@trans('hub/bot.list')</b></h3>
          @if (count($bots)>0)
            <ul class="hubs-list clearfix">
            @foreach ($bots as $bot)
              <li>
                <a href="@route('h::b::edit',$bot['id'])">
                  <img class="img-thumbnail" src="@asset($bot['image'])">
                  <span class="hubs-list-name" href="@route('h::b::edit',$bot['id'])">{{ $bot['name'] }}</span>
                  <center>
                  <input type="checkbox" class="bot" id="bot{{ $bot['id'] }}" @if ($bot->realStatus() == 1) checked @endif @if (in_array($bot->realStatus(),[-1,2])) disabled @endif />
                  </center>
                </a>
              </li>
            @endforeach
            </ul>
          @else
            <p>@trans('hub/bot.no_bot')</p>
          @endif
        </div>
    </div>
</div>
@endsection
