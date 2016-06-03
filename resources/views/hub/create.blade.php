@extends('hub.master')
@section('title','Create new hub')
@section('additionHeader')
  <link rel="stylesheet" href="{{ asset('public/libs/html5imageupload/html5imageupload.css') }}">
@endsection
@section('additionFooter')
  <script src="{{ asset('public/libs/html5imageupload/html5imageupload.js') }}"></script>
  <script>
    $('.dropzone').html5imageupload();
  </script>
@endsection
@section('body')
{!! content_header('Create new hub', [
    'Hub' => '#',
    'Create' => 'active'
]) !!}
<div class="row">
    <div class="col-sm-12">
        <div class="card-box">
            <h3 class="m-t-0 header-title"><b>Create new hub</b></h3>
            <p class="login-box-msg" style="margin-bottom:14px;"></p>
              {!! Form::open(['route' => 'h::create','class' => 'form-horizontal']) !!}
                <div class="form-group">
                  {!! Form::label('name', 'Name', ['class' => 'col-sm-2 control-label']) !!}
                  <div class="col-sm-10">
                    {!! Form::text('name', old('name'), ['class' => 'form-control']) !!}
                  </div>
                </div>
                <div class="form-group">
                  {!! Form::label('description', 'Description', ['class' => 'col-sm-2 control-label']) !!}
                  <div class="col-sm-10">
                    {!! Form::textarea('description', old('description'), ['class' => 'form-control']) !!}
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
                {!! Form::button('<span class="btn-label"><i class="fa fa-plus" aria-hidden="true"></i></span>Create', ['type' => 'submit', 'class' => 'btn btn-default pull-right btn-flat']) !!}
              {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
