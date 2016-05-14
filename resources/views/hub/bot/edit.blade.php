@extends('hub.master')
@section('title','Edit bot')
@section('additionHeader')
<link href="{{ asset('resources/assets/plugins/lou-multi-select/css/multi-select.css') }}" media="screen" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="{{ asset('resources/assets/plugins/html5imageupload/html5imageupload.css') }}">
@endsection
@section('additionFooter')
<script src="{{ asset('resources/assets/plugins/lou-multi-select/js/jquery.multi-select.js') }}" type="text/javascript"></script>
<script src="{{ asset('resources/assets/plugins/html5imageupload/html5imageupload.js') }}"></script>
<script>
  $('.dropzone').html5imageupload();
</script>
<script>
  $("[name='permissions[]']").multiSelect();
  $("[name='higherpermissions[]']").multiSelect();
  function botDeactivate() {
    bootbox.confirm("R u sure?", function(result) {
      if (result == true) {
      $.ajax({
          url : '{{ route('h::b::deactivate',$bot['id']) }}',
          type : 'post',
          dataType: 'json',
          data : { _token: '{{ csrf_token() }}' },
          success : function (response)
          {
              $('#botTus').text('Deactivated').removeClass('label-default label-info label-success label-primary').addClass('label-danger');
              botDeactivateBtn = $('#botDeactivateBtn').attr('id','botReactivateBtn').removeClass('btn-warning').addClass('bg-olive').attr('onclick','botReactivate()');
              botDeactivateBtn.find('i').removeClass('fa-ban').addClass('fa-check-square-o');
              botDeactivateBtn.find('span').text('Reactivate');
          }
        });
      }
    });
  }

  function botReactivate() {
    bootbox.confirm("R u sure?", function(result) {
      if (result == true) {
      $.ajax({
          url : '{{ route('h::b::reactivate',$bot['id']) }}',
          type : 'post',
          dataType: 'json',
          data : { _token: '{{ csrf_token() }}' },
          success : function (response)
          {
              $('#botTus').text('Reactivated').removeClass('label-default label-info label-success label-danger').addClass('label-primary');
              botReactivateBtn = $('#botReactivateBtn').attr('id','botDeactivateBtn').addClass('btn-warning').removeClass('bg-olive').attr('onclick','botDeactivate()');
              botReactivateBtn.find('i').addClass('fa-ban').removeClass('fa-check-square-o');
              botReactivateBtn.find('span').text('Deactivate');
          }
        });
      }
    });
  }

  function botDelete() {
    bootbox.confirm("R u sure?", function(result) {
      if (result == true) {
      $.ajax({
          url : '{{ route('h::b::destroy',$bot['id']) }}',
          type : 'post',
          dataType: 'json',
          data : { _token: '{{ csrf_token() }}' },
          success : function (response)
          {
              window.location.href = '{{ route('h::b::index') }}';
          }
        });
      }
    });
  }
</script>
@endsection
@section('body')
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Edit Bot
    <small>Edit your bot's infomation</small>
  </h1>
  {{breadcrumb([
    'Hub' => route('h::edit'),
    'Bot' => route('h::b::index'),
    'Edit' => 'active'
  ])}}</section>
<!-- Main content -->
<section class="content">
  <!-- Default box -->
  <div class="box">
    <div class="box-header">
      <h3 class="box-title">Edit your bot</h3>
    </div>
    {!! Form::open(['route' => ['h::b::edit',$bot['id']], 'files' => true, 'class' => 'form-horizontal']) !!}
    <div class="box-body">
      @if (session('success') === true)
      <div class="alert alert-success alert-dismissible fade in">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <i class="fa fa-check" aria-hidden="true"></i>&nbsp;
        Saved
      </div>
      @endif
      {!! Form::hidden('id', $bot['id']) !!}
      <div class="form-group margin-bottom-sm">
        {!! Form::label('status', 'Status',['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
          <?php
            switch ($bot['status']) {
              case -1:
                echo '<h5><span class="label label-danger" id="botTus">Deactivated</span></h5>';
                break;
              case 0:
                if ($bot['true'] == 1) {
                  echo '<h5><span class="label label-default" id="botTus">Turned off</span></h5>';
                } else {
                  echo '<h5><span class="label label-info" id="botTus">Turning off</span></h5>';
                }
                break;
              case 1:
                if ($bot['true'] == 1) {
                  echo '<h5><span class="label label-success" id="botTus">Turned on</span></h5>';
                } else {
                  echo '<h5><span class="label label-primary" id="botTus">Turning on</span></h5>';
                }
                break;
            }
          ?>
        </div>
      </div>
      <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
        {!! Form::label('name', 'Name', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
          {!! Form::text('name', $bot['name'], ['class' => 'form-control']) !!}
          @if ($errors->has('name'))
          <span class="help-block margin-bottom-none">
            {{ $errors->first('name') }}
          </span>
          @endif
        </div>
      </div>
      <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
        {!! Form::label('description', 'Description', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
          {!! Form::textarea('description', $bot['description'], ['class' => 'form-control']) !!}
          @if ($errors->has('description'))
          <span class="help-block margin-bottom-none">
            {{ $errors->first('description') }}
          </span>
          @endif
        </div>
      </div>
      <div class="form-group">
        {!! Form::label('image', 'Image', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
          <div class="dropzone image-dropzone" data-image="{{ asset($bot['image']) }}" data-ghost="false" data-canvas="true" data-originalsize="false" data-ajax="false" data-width="200" data-height="200">
            {!! Form::file('image') !!}
          </div>
        </div>
      </div>
      <div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
        {!! Form::label('type', 'Type', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
          {!! Form::select('type', [
            '1' => 'Flip-bot',
            '2' => 'Push-bot',
            '3' => 'Twist-bot',
            '4' => 'Plug-bot',
            '5' => 'Sense-bot',
            '6' => 'IR-bot'
            ], $bot['type'], ['class' => 'form-control', 'readonly' => 'readonly']) !!}
          </div>
        </div>
        <div class="form-group">
          {!! Form::label('token', 'Token', ['class' => 'col-sm-2 control-label']) !!}
          <div class="col-sm-10">
            {!! Form::text('token', $bot['token'], ['class' => 'form-control','readonly' => 'readonly']) !!}
          </div>
        </div>
        <div class="form-group{{ $errors->has('permissions') ? ' has-error' : '' }}">
          {!! Form::label('permissions', 'Permissions', ['class' => 'col-sm-2 control-label']) !!}
          <div class="col-sm-10">
            {!! Form::select('permissions[]', $users, $selected, ['class' => 'form-control', 'multiple' => 'multiple']) !!}
            @if ($errors->has('permissions'))
            <span class="help-block margin-bottom-none">
              {{ $errors->first('permissions') }}
            </span>
            @else
            <span class="help-block margin-bottom-none">Users can manage this bot</span>
            @endif
          </div>
        </div>
        <div class="form-group{{ $errors->has('higherpermissions') ? ' has-error' : '' }}">
          {!! Form::label('higherpermissions', 'Higher permissions', ['class' => 'col-sm-2 control-label']) !!}
          <div class="col-sm-10">
            {!! Form::select('higherpermissions[]', $users, $selected2, ['class' => 'form-control', 'multiple' => 'multiple']) !!}
            @if ($errors->has('higherpermissions'))
            <span class="help-block margin-bottom-none">
              {{ $errors->first('higherpermissions') }}
            </span>
            @else
            <span class="help-block margin-bottom-none">Users can manage this bot</span>
            @endif
          </div>
        </div>
      </div>
      <!-- /.box-body -->
      <div class="box-footer">
        {!! Form::button('<i class="fa fa-floppy-o" aria-hidden="true"></i>&nbsp;&nbsp;Save', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
        {!! Form::button('<i class="fa fa-trash" aria-hidden="true"></i>&nbsp;&nbsp;Delete', ['type' => 'button', 'class' => 'btn btn-danger pull-right', 'onclick' => 'botDelete()']) !!}</a>
        @if ($bot['status'] != -1)
          {!! Form::button('<i class="fa fa-ban" aria-hidden="true"></i>&nbsp;&nbsp;<span>Deactivate</span>', ['type' => 'button', 'class' => 'btn btn-warning pull-right margin-right-sm','id' => 'botDeactivateBtn','onclick' => 'botDeactivate()']) !!}
        @else
          {!! Form::button('<i class="fa fa-check-square-o" aria-hidden="true"></i></i>&nbsp;&nbsp;<span>Reactivate</span>', ['type' => 'button', 'class' => 'btn bg-olive pull-right margin-right-sm','id' => 'botReactivateBtn','onclick' => 'botReactivate()']) !!}
        @endif
      </div>
      <!-- /.box-footer -->
    {!! Form::close() !!}
  </div>
</section>
<!-- /.content -->
@endsection
