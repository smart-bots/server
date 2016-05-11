@extends('hub.master')
@section('title','Hub Setting')
@section('additionHeader')
	<link rel="stylesheet" href="{{ asset('resources/assets/plugins/html5imageupload/html5imageupload.css') }}">
@endsection
@section('additionFooter')
	<script src="{{ asset('resources/assets/plugins/html5imageupload/html5imageupload.js') }}"></script>
	<script>
	$('.dropzone').html5imageupload();
	function renewToken() {
		var x = 50;
	    var s = '';
	    while(s.length<x&&x>0){
	        var r = Math.random();
	        s+= (r<0.1?Math.floor(r*100):String.fromCharCode(Math.floor(r*26) + (r>0.5?97:65)));
	    }
	    $("[name='token_old']").attr('name','token').val(s);
	}
	</script>
@endsection
@section('body')
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		Hub Edit
		<small>Setting your hub</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> {{ $hub['name']}}</a></li>
		<li class="active">Setting</li>
	</ol>
</section>
<!-- Main content -->
<section class="content">
	<!-- Default box -->
	<div class="box">
		<div class="box-header">
			<h3 class="box-title">Basic info</h3>
		</div>
		<!-- /.box-header -->
		{!! Form::open(['route' => 'h::edit','files' => true,'class' => 'form-horizontal']) !!}
		<div class="box-body">
			@if (isset($success) && $success == true)
			<div class="alert alert-success alert-dismissible fade in">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<i class="fa fa-check" aria-hidden="true"></i>&nbsp;
				Saved
			</div>
			@endif
			<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
				{!! Form::label('name', 'Hub name', ['class' => 'col-sm-2 control-label']) !!}
				<div class="col-sm-10">
					{!! Form::text('name', $hub['name'], ['class' => 'form-control']) !!}
					@if ($errors->has('name'))
					<span class="help-block margin-bottom-none">
						{{ $errors->first('name') }}
					</span>
					@endif
				</div>
			</div>
			<div class="form-group{{ $errors->has('token') ? ' has-error' : '' }}">
				{!! Form::label('token', 'Token', ['class' => 'col-sm-2 control-label']) !!}
				<div class="col-sm-10">
					<div class="input-group">
						{!! Form::text('token_old', $hub['token'], ['class' => 'form-control', 'readonly' => 'readonly']) !!}
						<span class="input-group-btn">
							{!! Form::button('<i class="fa fa-refresh" aria-hidden="true"></i>&nbsp;&nbsp;Renew', ['type' => 'button', 'class' => 'btn bg-olive', 'onclick' => 'renewToken();']) !!}
						</span>
					</div>
					@if ($errors->has('token'))
					<span class="help-block margin-bottom-none">
						{{ $errors->first('token') }}
					</span>
					@else
					<p class="help-block margin-bottom-none">This token is quite important, so dont let it fall into the wrong hands</p>
					@endif
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
			<div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
				{!! Form::label('description', 'Description', ['class' => 'col-sm-2 control-label']) !!}
				<div class="col-sm-10">
					{!! Form::textarea('description', $hub['description'], ['class' => 'form-control']) !!}
					@if ($errors->has('description'))
					<span class="help-block margin-bottom-none">
						{{ $errors->first('description') }}
					</span>
					@endif
				</div>
			</div>
		</div>
		<!-- /.box-body -->
		<div class="box-footer">
			{!! Form::button('<i class="fa fa-floppy-o" aria-hidden="true"></i>&nbsp;&nbsp;Save', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
			{!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i>&nbsp;&nbsp;Delete', ['type' => 'button', 'class' => 'btn btn-danger pull-right', 'onclick' => 'hubDelete()']) !!}
			@if ($hub['status'] == 1)
				{!! Form::button('<i class="fa fa-ban" aria-hidden="true"></i>&nbsp;&nbsp;<span>Deactivate</span>', ['type' => 'button', 'class' => 'btn btn-warning pull-right margin-right-sm','id' => 'hubDeactivateBtn','onclick' => 'hubDeactivate()']) !!}
			@else
				{!! Form::button('<i class="fa fa-check-square-o" aria-hidden="true"></i></i>&nbsp;&nbsp;<span>Reactivate</span>', ['type' => 'button', 'class' => 'btn bg-olive pull-right margin-right-sm','id' => 'hubReactivateBtn','onclick' => 'hubReactivate()']) !!}
			@endif
		</div>
		<!-- /.box-footer -->
		{!! Form::close() !!}
		<!-- /.box-body -->
	</div>
	<!-- /.box -->
</section>
<!-- /.content -->
@endsection
