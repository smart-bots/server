@extends('hub.master')
@section('title',trans('account/edit.title'))
@section('additionHeader')
    <link rel="stylesheet" href="@asset('public/libs/html5imageupload/html5imageupload.css')">
@endsection
@section('additionFooter')
    <script src="@asset('public/libs/html5imageupload/html5imageupload.js')"></script>
    <script>
        $('.dropzone').html5imageupload();

        function edit() {
            $.ajax({
                url : '@route('a::edit')',
                type : 'post',
                data : $('[name=edit-form]').serializeArray(),
                dataType : 'json',
                success : function (response)
                {
                    $('[name=edit-form]').validate(response, ['username,avatar']);
                }
            });
            return false;
        }
    </script>
@endsection
@section('body')
@header(trans('account/edit.title'), [
    trans('account/edit.account') => 'active'])
<div class="row">
    <div class="col-sm-12">
        <div class="card-box">
        {!! Form::open(['route' => 'a::edit','name'=> 'edit-form', 'class' => 'form-horizontal', 'onsubmit' => 'return edit()']) !!}
            <div class="form-group">
                {!! Form::label('avatar', trans('account/edit.avatar'), ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                    <div class="dropzone image-dropzone" data-image="@asset($user['avatar'])" data-ghost="false" data-canvas="true" data-originalsize="false" data-ajax="false" data-width="200" data-height="200">
                        {!! Form::file('image') !!}
                    </div>
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('username', trans('account/edit.username'), ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                    {!! Form::text('username', $user['username'], ['class' => 'form-control', 'readonly' => 'readonly']) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('name', trans('account/edit.name'), ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                    {!! Form::text('name', $user['name'], ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('email', trans('account/edit.email'), ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                    {!! Form::text('email', $user['email'], ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('phone', trans('account/edit.phone'), ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                    <div class="input-group">
                        {!! Form::text('phone', $user['phone'], ['class' => 'form-control']) !!}
                        <span class="input-group-btn">
                            {!! Form::button('<i class="fa fa-check-square" aria-hidden="true"></i>&nbsp;&nbsp;'.trans('account/edit.phone_verify'), ['type' => 'button', 'class' => 'btn btn-danger waves-effect waves-light']) !!}
                        </span>
                    </div>
                </div>
            </div>

            {!! Form::button('<span class="btn-label"><i class="fa fa-floppy-o" aria-hidden="true"></i></span>'.trans('account/edit.edit'), ['type' => 'submit', 'class' => 'btn btn-primary waves-effect waves-light']) !!}
            <a href="@route('a::changePass')">{!! Form::button('<span class="btn-label"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span>Change Password', ['type' => 'button', 'class' => 'btn btn-default pull-right waves-effect waves-light']) !!}</a>
        {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
