@extends('hub.master')
@section('title','Add new bot')
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
  function verifyToken() {
    return false;
  }
</script>
@endsection
@section('body')
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Create Bot
    <small>Add a new bot to your hub</small>
  </h1>
  {{breadcrumb([
    'Hub' => route('h::edit'),
    'Bot' => route('h::b::index'),
    'Create' => 'active'
  ])}}
</section>
<!-- Main content -->
<section class="content">
  <!-- Default box -->
  <div class="box">
    <div class="box-header">
      <h3 class="box-title">Add new bots</h3>
    </div>
    {!! Form::open(['route' => 'h::b::create', 'files' => true, 'class' => 'form-horizontal']) !!}
    <div class="box-body">
      <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
        {!! Form::label('name', 'Name', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
          {!! Form::text('name', old('name'), ['class' => 'form-control']) !!}
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
          {!! Form::textarea('description', old('description'), ['class' => 'form-control']) !!}
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
          <div class="dropzone image-dropzone" data-ghost="false" data-canvas="true" data-originalsize="false" data-ajax="false" data-width="200" data-height="200">
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
            ], old('type'), ['class' => 'form-control']) !!}
            @if ($errors->has('type'))
            <span class="help-block margin-bottom-none">
              {{ $errors->first('type') }}
            </span>
            @endif
          </div>
        </div>
        <div class="form-group{{ $errors->has('token') ? ' has-error' : '' }}">
          {!! Form::label('token', 'Token', ['class' => 'col-sm-2 control-label']) !!}
          <div class="col-sm-10">
            <div class="input-group">
              {!! Form::text('token', old('token'), ['class' => 'form-control']) !!}
              <span class="input-group-btn">
                {!! Form::button('<i class="fa fa-bullseye" aria-hidden="true"></i>&nbsp;&nbsp;Verify', ['type' => 'button', 'class' => 'btn bg-olive', 'onclick' => 'verifyToken();']) !!}
              </span>
            </div>
            @if ($errors->has('token'))
            <span class="help-block margin-bottom-none">
              {{ $errors->first('token') }}
            </span>
            @endif
          </div>
        </div>
        <div class="form-group{{ $errors->has('permissions') ? ' has-error' : '' }}">
          {!! Form::label('permissions', 'Permissions', ['class' => 'col-sm-2 control-label']) !!}
          <div class="col-sm-10">
            {!! Form::select('permissions[]', $users, old('permissions'), ['class' => 'form-control', 'multiple' => 'multiple']) !!}
            @if ($errors->has('permissions'))
            <span class="help-block margin-bottom-none">
              {{ $errors->first('permissions') }}
            </span>
            @else
            <span class="help-block margin-bottom-none">Users can view/control this bot</span>
            @endif
          </div>
        </div>
        <div class="form-group{{ $errors->has('higherpermissions') ? ' has-error' : '' }}">
          {!! Form::label('higherpermissions', 'Higher permissions', ['class' => 'col-sm-2 control-label']) !!}
          <div class="col-sm-10">
            {!! Form::select('higherpermissions[]', $users, old('higherpermissions'), ['class' => 'form-control', 'multiple' => 'multiple']) !!}
            @if ($errors->has('higherpermissions'))
            <span class="help-block margin-bottom-none">
              {{ $errors->first('higherpermissions') }}
            </span>
            @else
            <span class="help-block margin-bottom-none">Users can edit/delete this bot</span>
            @endif
          </div>
        </div>
      </div>
      <!-- /.box-body -->
      <div class="box-footer">
        {!! Form::button('<i class="fa fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;Add', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
      </div>
      <!-- /.box-footer -->
    {!! Form::close() !!}
  </div>
</section>
<!-- /.content -->
@endsection
