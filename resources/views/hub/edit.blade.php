@extends('hub.master')
@section('title','Hub edit')
@section('additionHeader')
	<link rel="stylesheet" href="{{ asset('public/libs/html5imageupload/html5imageupload.css') }}">
@endsection
@section('additionFooter')
	<script src="{{ asset('public/libs/html5imageupload/html5imageupload.js') }}"></script>
	<script>
	$('.dropzone').html5imageupload();
	function renewToken() {
		var x = 50;
	    var s = '';
	    while(s.length<x&&x>0){
	        var r = Math.random();
	        s+= (r<0.1?Math.floor(r*100):String.fromCharCode(Math.floor(r*26) + (r>0.5?97:65)));
	    }
	    $("[name='token']").val(s);
	}
	</script>
@endsection
@section('body')
{!! content_header('Hub edit', [
    'Hub' => '#',
    'Edit' => 'active'
]) !!}
<div class="row">
    <div class="col-sm-12">
        <div class="card-box">
        <h3 class="m-t-0 header-title"><b>Edit hub's settings</b></h3>
		{!! Form::open(['route' => 'h::edit','class' => 'form-horizontal']) !!}
		<div class="box-body">
			<div class="form-group">
				{!! Form::label('name', 'Name', ['class' => 'col-sm-2 control-label']) !!}
				<div class="col-sm-10">
					{!! Form::text('name', $hub['name'], ['class' => 'form-control']) !!}
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('token', 'Token', ['class' => 'col-sm-2 control-label']) !!}
				<div class="col-sm-10">
					<div class="input-group">
						{!! Form::text('token', $hub['token'], ['class' => 'form-control', 'readonly' => 'readonly']) !!}
						<span class="input-group-btn">
							{!! Form::button('<i class="fa fa-refresh" aria-hidden="true"></i>&nbsp;&nbsp;Renew', ['type' => 'button', 'class' => 'btn btn-default waves-effect waves-light', 'onclick' => 'renewToken();']) !!}
						</span>
					</div>
					<p class="help-block margin-bottom-none">This token is quite important, so dont let it fall into the wrong hands</p>
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('image', 'Image', ['class' => 'col-sm-2 control-label']) !!}
				<div class="col-sm-10">
					<div class="dropzone image-dropzone" data-image="{{ asset($hub['image']) }}" data-ghost="false" data-canvas="true" data-originalsize="false" data-ajax="false" data-width="200" data-height="200">
						{!! Form::file('image') !!}
					</div>
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('description', 'Description', ['class' => 'col-sm-2 control-label']) !!}
				<div class="col-sm-10">
					{!! Form::textarea('description', $hub['description'], ['class' => 'form-control']) !!}
				</div>
			</div>
			{!! Form::button('<i class="fa fa-floppy-o" aria-hidden="true"></i>&nbsp;&nbsp;Save', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
			{!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i>&nbsp;&nbsp;Delete', ['type' => 'button', 'class' => 'btn btn-danger pull-right', 'onclick' => 'hubDelete()']) !!}
			@if ($hub['status'] == 1)
				{!! Form::button('<i class="fa fa-ban" aria-hidden="true"></i>&nbsp;&nbsp;<span>Deactivate</span>', ['type' => 'button', 'class' => 'btn btn-warning pull-right m-r-5','id' => 'hubDeactivateBtn','onclick' => 'hubDeactivate()']) !!}
			@else
				{!! Form::button('<i class="fa fa-check-square-o" aria-hidden="true"></i></i>&nbsp;&nbsp;<span>Reactivate</span>', ['type' => 'button', 'class' => 'btn btn-default pull-right m-r-5','id' => 'hubReactivateBtn','onclick' => 'hubReactivate()']) !!}
			@endif
		{!! Form::close() !!}
@endsection
