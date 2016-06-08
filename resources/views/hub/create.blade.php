@extends('hub.master')
@section('title','Create new hub')
@section('additionHeader')
  <link rel="stylesheet" href="{{ asset('public/libs/html5imageupload/html5imageupload.css') }}">
@endsection
@section('additionFooter')
  <script src="{{ asset('public/libs/html5imageupload/html5imageupload.js') }}"></script>
  <script>
    $('.dropzone').html5imageupload();


    function hubCreate() {
      $.ajax({
          url : '{{ route('h::create') }}',
          type : 'post',
          data : $('[name=hub-create-form]').serializeArray(),
          dataType : 'json',
          success : function (response)
          {
              $('[name=hub-create-form]').validate(response, [], function () {
                window.location.href = response['href'];
              });
          }
      });
      return false;
    }
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
            <h3 class="m-t-0 m-b-10 header-title"><b>Create new hub</b></h3>
              {!! Form::open(['route' => 'h::create','name' => 'hub-create-form','class' => 'form-horizontal', 'onsubmit' => 'return hubCreate()']) !!}
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
