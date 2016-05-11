@extends('hub.master')
@section('title','Edit profile')
@section('additionHeader')
	<link rel="stylesheet" href="{{ asset('resources/assets/plugins/html5imageupload/html5imageupload.css') }}">
@endsection
@section('additionFooter')
	<script src="{{ asset('resources/assets/plugins/html5imageupload/html5imageupload.js') }}"></script>
	<script>
	$('.dropzone').html5imageupload();
	</script>
@endsection
@section('body')
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		Profile edit
		<small>Edit your infomation</small>
	</h1>
	{{breadcrumb([
		'Account' => 'active'
		])}}
</section>
<!-- Main content -->
<section class="content">
	<!-- Default box -->
	<div class="box">
		<div class="box-header">
			<h3 class="box-title">Basic info</h3>
		</div>
		<!-- /.box-header -->
		{!! Form::open(['route' => 'a::edit','class' => 'form-horizontal']) !!}
		<div class="box-body">
			@if (isset($success) && $success == true)
			<div class="alert alert-success alert-dismissible fade in">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<i class="fa fa-check" aria-hidden="true"></i>&nbsp;
				Saved
			</div>
			@endif
			<div class="form-group">
				{!! Form::label('avatar', 'Avatar', ['class' => 'col-sm-2 control-label']) !!}
				<div class="col-sm-10">
					<div class="dropzone image-dropzone" data-image="{{ asset($user['avatar']) }}" data-ghost="false" data-canvas="true" data-originalsize="false" data-ajax="false" data-width="200" data-height="200">
						{!! Form::file('image') !!}
					</div>
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('username', 'Username', ['class' => 'col-sm-2 control-label']) !!}
				<div class="col-sm-10">
					{!! Form::text('username', $user['username'], ['class' => 'form-control', 'readonly' => 'readonly']) !!}
				</div>
			</div>
			<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
				{!! Form::label('name', 'Name', ['class' => 'col-sm-2 control-label']) !!}
				<div class="col-sm-10">
					{!! Form::text('name', $user['name'], ['class' => 'form-control']) !!}
					@if ($errors->has('name'))
					<span class="help-block margin-bottom-none">
						{{ $errors->first('name') }}
					</span>
					@endif
				</div>
			</div>
			<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
				{!! Form::label('email', 'Email', ['class' => 'col-sm-2 control-label']) !!}
				<div class="col-sm-10">
					{!! Form::text('email', $user['email'], ['class' => 'form-control']) !!}
					@if ($errors->has('email'))
					<span class="help-block margin-bottom-none">
						{{ $errors->first('email') }}
					</span>
					@endif
				</div>
			</div>
			<div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
				{!! Form::label('phone', 'Phone', ['class' => 'col-sm-2 control-label']) !!}
				<div class="col-sm-10">
					{!! Form::text('phone', $user['phone'], ['class' => 'form-control']) !!}
					@if ($errors->has('phone'))
					<span class="help-block margin-bottom-none">
						{{ $errors->first('phone') }}
					</span>
					@endif
				</div>
			</div>
		</div>
		<!-- /.box-body -->
		<div class="box-footer">
			{!! Form::button('<i class="fa fa-floppy-o" aria-hidden="true"></i>&nbsp;&nbsp;Save', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
			<a href="{{ route('a::changePass') }}"{!! Form::button('<i class="fa fa-pencil-square-o" aria-hidden="true"></i>&nbsp;&nbsp;Change Password', ['type' => 'button', 'class' => 'btn bg-olive pull-right']) !!}</a>
			</div>
		<!-- /.box-footer -->
		{!! Form::close() !!}
		<!-- /.box-body -->
	</div>
	<!-- /.box -->
</section>
<!-- /.content -->
@endsection
