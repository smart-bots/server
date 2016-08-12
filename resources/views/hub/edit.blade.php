@extends('hub.master')
@section('title',trans('hub/hub.edit'))
@section('additionHeader')
	<link rel="stylesheet" href="@asset('public/libs/html5imageupload/html5imageupload.css')">
@endsection
@section('additionFooter')
	<script src="@asset('public/libs/html5imageupload/html5imageupload.js')"></script>
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

	function hubDelete() {
	    swal({
	        title: '@trans('hub/hub.delete_title')',
	        text: '@trans('hub/hub.delete_text')',
	        type: "error",
	        showCancelButton: true,
	        confirmButtonText: '@trans('hub/hub.delete_confirm')',
	        closeOnConfirm: false }, function() {
	            $.ajax({
	                url : '@route('h::destroy')',
	                type : 'get',
	                success : function (response)
	                {
	                    window.location.href="@route('h::index')";
	                }
	            });
	        });
	}

	function hubEdit() {
		$.ajax({
		    url : '@route('h::edit')',
		    type : 'post',
		    data : $('[name=hub-edit-form]').serializeArray(),
		    dataType : 'json',
		    success : function (response)
		    {
		        $('[name=hub-edit-form]').validate(response, ['image']);
		    }
		});
		return false;
	}
	</script>
@endsection
@section('body')
@header(trans('hub/hub.edit'), [
    'Hub' => '#',
    'Edit' => 'active'
])
<div class="row">
    <div class="col-sm-12">
        <div class="card-box">
        <h3 class="m-t-0 m-b-15 header-title"><b>@trans('hub/hub.edit_tip')</b></h3>
		{!! Form::open(['route' => 'h::edit','name' => 'hub-edit-form', 'class' => 'form-horizontal', 'onsubmit' => 'return hubEdit()']) !!}
		<div class="box-body">
			<div class="form-group">
				{!! Form::label('name', trans('hub/hub.name'), ['class' => 'col-sm-2 control-label']) !!}
				<div class="col-sm-10">
					{!! Form::text('name', $hub['name'], ['class' => 'form-control']) !!}
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('token', trans('hub/hub.token'), ['class' => 'col-sm-2 control-label']) !!}
				<div class="col-sm-10">
					<div class="input-group">
						{!! Form::text('token', $hub['token'], ['class' => 'form-control', 'readonly' => 'readonly']) !!}
						<span class="input-group-btn">
							{!! Form::button('<i class="fa fa-refresh" aria-hidden="true"></i>&nbsp;&nbsp;Renew', ['type' => 'button', 'class' => 'btn btn-default waves-effect waves-light', 'onclick' => 'renewToken();']) !!}
						</span>
					</div>
					<p class="help-block margin-bottom-none">@trans('hub/hub.token_tip')</p>
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('image', trans('hub/hub.image'), ['class' => 'col-sm-2 control-label']) !!}
				<div class="col-sm-10">
					<div class="dropzone image-dropzone" data-image="@asset($hub['image'])" data-ghost="false" data-canvas="true" data-originalsize="false" data-ajax="false" data-width="200" data-height="200">
						{!! Form::file('image') !!}
					</div>
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('description', trans('hub/hub.description'), ['class' => 'col-sm-2 control-label']) !!}
				<div class="col-sm-10">
					{!! Form::textarea('description', $hub['description'], ['class' => 'form-control']) !!}
				</div>
			</div>
			<div class="form-group">
			  {!! Form::label('timezone', trans('hub/hub.timezone'), ['class' => 'col-sm-2 control-label']) !!}
			  <div class="col-sm-10">
			    {!! Timezone::selectForm($hub['timezone'], null, array('class' => 'form-control', 'name' => 'timezone')) !!}
			  </div>
			</div>
			{!! Form::button('<i class="fa fa-floppy-o" aria-hidden="true"></i>&nbsp;&nbsp;'.trans('hub/hub.save_btn'), ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
			{!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i>&nbsp;&nbsp;'.trans('hub/hub.delete_btn'), ['type' => 'button', 'class' => 'btn btn-danger pull-right', 'onclick' => 'hubDelete()']) !!}
			@if ($hub['status'] == 1)
				{!! Form::button('<i class="fa fa-ban" aria-hidden="true"></i>&nbsp;&nbsp;<span>'.trans('hub/hub.deactivate_btn').'</span>', ['type' => 'button', 'class' => 'btn btn-warning pull-right m-r-5','id' => 'hubDeactivateBtn','onclick' => 'hubDeactivate()']) !!}
			@else
				{!! Form::button('<i class="fa fa-check-square-o" aria-hidden="true"></i></i>&nbsp;&nbsp;<span>'.trans('hub/hub.activate_btn').'</span>', ['type' => 'button', 'class' => 'btn btn-default pull-right m-r-5','id' => 'hubReactivateBtn','onclick' => 'hubReactivate()']) !!}
			@endif
		{!! Form::close() !!}
@endsection
