@extends('hub.master')
@section('title','Add new member')
@section('additionHeader')
<link href="{{ asset('resources/assets/plugins/lou-multi-select/css/multi-select.css') }}" media="screen" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="{{ asset('resources/assets/plugins/iCheck/square/blue.css') }}">
<style>
.table td {
  text-align: center;
  font-weight: bold;
}
</style>
@endsection
@section('additionFooter')
<script src="{{ asset('resources/assets/plugins/typeahead/typeahead.js') }}" type="text/javascript"></script>
<script src="{{ asset('resources/assets/plugins/handlebars/handlebars.js') }}" type="text/javascript"></script>
<script src="{{ asset('resources/assets/plugins/lou-multi-select/js/jquery.multi-select.js') }}" type="text/javascript"></script>
<script src="{{ asset('resources/assets/plugins/iCheck/icheck.min.js') }}"></script>
<script>
$("[name='permissions[]']").multiSelect();
$("[name='higherpermissions[]']").multiSelect();
$('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue'
    });
function memDeactivate(id) {
    bootbox.confirm("R u sure?", function(result) {
      if (result == true) {
      $.ajax({
          url : '{{ route('h::m::deactivate') }}',
          type : 'post',
          dataType: 'json',
          data : {
            _token: '{{ csrf_token() }}',
            id: id
          },
          success : function (response)
          {
              $('#memTus').text('Deactivated').removeClass('label-primary').addClass('label-danger');
              memDeactivateBtn = $('#memDeactivateBtn').attr('id','memReactivateBtn').removeClass('btn-warning').addClass('bg-olive').attr('onclick','memReactivate({{ $mem['id'] }})');
              memDeactivateBtn.find('i').removeClass('fa-ban').addClass('fa-check-square-o');
              memDeactivateBtn.find('span').text('Reactivate');
          }
        });
      }
    });
  }

  function memReactivate(id) {
    bootbox.confirm("R u sure?", function(result) {
      if (result == true) {
      $.ajax({
          url : '{{ route('h::m::reactivate') }}',
          type : 'post',
          dataType: 'json',
          data : {
            _token: '{{ csrf_token() }}',
            id: id
          },
          success : function (response)
          {
              $('#memTus').text('Activated').removeClass('label-danger').addClass('label-primary');
              memReactivateBtn = $('#memReactivateBtn').attr('id','memDeactivateBtn').addClass('btn-warning').removeClass('bg-olive').attr('onclick','memDeactivate({{ $mem['id'] }})');
              memReactivateBtn.find('i').addClass('fa-ban').removeClass('fa-check-square-o');
              memReactivateBtn.find('span').text('Deactivate');
          }
        });
      }
    });
  }

  function memDelete(id) {
    bootbox.confirm("R u sure?", function(result) {
      if (result == true) {
      $.ajax({
          url : '{{ route('h::m::destroy') }}',
          type : 'post',
          dataType: 'json',
          data : {
            _token: '{{ csrf_token() }}',
            id: id
          },
          success : function (response)
          {
              window.location.href = '{{ route('h::m::index') }}';
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
    Hub Member
    <small>Add new member</small>
  </h1>
  {{breadcrumb([
    'Hub' => route('h::edit'),
    'Member' => route('h::m::index'),
    'Edit' => 'active'
  ])}}
</section>
<!-- Main content -->
<section class="content">
  <!-- Default box -->
  <div class="box">
    <div class="box-header">
      <h3 class="box-title">Add new member</h3>
    </div>
    {!! Form::open(['route' => ['h::m::edit',$mem['id']], 'files' => true, 'class' => 'form-horizontal']) !!}
      <div class="box-body">
        @if (session('success') === true)
        <div class="alert alert-success alert-dismissible fade in">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <i class="fa fa-check" aria-hidden="true"></i>&nbsp;
          Saved
        </div>
        @endif
        <div class="form-group margin-bottom-sm">
        {!! Form::label('status', 'Status',['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
          <?php
            switch ($mem['status']) {
              case 0:
                echo '<h5><span class="label label-danger" id="memTus">Deactivated</span></h5>';
                break;
              case 1:
                echo '<h5><span class="label label-primary" id="memTus">Activated</span></h5>';
                break;
            }
          ?>
        </div>
      </div>
        <div class="form-group">
          {!! Form::label('username', 'Members\'s username', ['class' => 'col-sm-2 control-label']) !!}
          <div class="col-sm-10">
            {!! Form::text('username', $username, ['class' => 'form-control', 'readonly' => 'readonly']) !!}
          </div>
        </div>
        <div class="form-group">
          {!! Form::label('username', 'Members\'s permissions', ['class' => 'col-sm-2 control-label']) !!}
          <div class="col-sm-10">
            {!! Form::select('permissions[]', $bots, $selected, ['class' => 'form-control', 'multiple' => 'multiple']) !!}
            @if ($errors->has('permissions'))
            <span class="help-block margin-bottom-none">
              {{ $errors->first('permissions') }}
            </span>
            @endif
          </div>
        </div>
        <div class="form-group">
          {!! Form::label('username', 'Members\'s higher-permissions', ['class' => 'col-sm-2 control-label']) !!}
          <div class="col-sm-10">
            {!! Form::select('higherpermissions[]', $bots, $selected2, ['class' => 'form-control', 'multiple' => 'multiple']) !!}
            @if ($errors->has('higherpermissions'))
            <span class="help-block margin-bottom-none">
              {{ $errors->first('higherpermissions') }}
            </span>
            @endif
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-12">
            <table class="table table-bordered table-striped margin-bottom-none">
            <thead>
              <tr>
              <td></td>
              <td width="1%">Add/Create</td>
              <td width="1%">View&nbsp;/Control</td>
              <td width="1%">Edit/Delete</td>
              </tr>
            </thead>
            <tbody>
            <tr>
              <td class="text-left">Hub</td>
              <td></td>
              <td>{!! Form::checkbox('hubpermissions[]', 1, in_array(1,$hubperms)) !!}</td>
              <td>{!! Form::checkbox('hubpermissions[]', 2, in_array(2,$hubperms)) !!}</td>
            </tr>
            <tr>
              <td class="text-left">Bots</td>
              <td>{!! Form::checkbox('hubpermissions[]', 3, in_array(3,$hubperms)) !!}</td>
              <td>{!! Form::checkbox('hubpermissions[]', 4, in_array(4,$hubperms)) !!}</td>
              <td>{!! Form::checkbox('hubpermissions[]', 5, in_array(5,$hubperms)) !!}</td>
            </tr>
            <tr>
              <td class="text-left">Schedules</td>
              <td>{!! Form::checkbox('hubpermissions[]', 6, in_array(6,$hubperms)) !!}</td>
              <td>{!! Form::checkbox('hubpermissions[]', 7, in_array(7,$hubperms)) !!}</td>
              <td>{!! Form::checkbox('hubpermissions[]', 8, in_array(8,$hubperms)) !!}</td>
            </tr>
            <tr>
              <td class="text-left">Automations</td>
              <td>{!! Form::checkbox('hubpermissions[]', 9, in_array(9,$hubperms)) !!}</td>
              <td>{!! Form::checkbox('hubpermissions[]', 10, in_array(10,$hubperms)) !!}</td>
              <td>{!! Form::checkbox('hubpermissions[]', 11, in_array(11,$hubperms)) !!}</td>
            </tr>
            <tr>
              <td class="text-left">Members</td>
              <td>{!! Form::checkbox('hubpermissions[]', 12, in_array(12,$hubperms)) !!}</td>
              <td>{!! Form::checkbox('hubpermissions[]', 13, in_array(13,$hubperms)) !!}</td>
              <td>{!! Form::checkbox('hubpermissions[]', 13, in_array(14,$hubperms)) !!}</td>
            </tr>
            </tbody>
            </table>
          </div>
        </div>

      </div>
      <!-- /.box-body -->
      <div class="box-footer">
        {!! Form::button('<i class="fa fa-floppy-o" aria-hidden="true"></i>&nbsp;&nbsp;Save', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
        {!! Form::button('<i class="fa fa-trash" aria-hidden="true"></i>&nbsp;&nbsp;Delete', ['type' => 'button', 'class' => 'btn btn-danger pull-right', 'onclick' => 'memDelete('.$mem['id'].')']) !!}</a>
        @if ($mem['status'] != 0)
          {!! Form::button('<i class="fa fa-ban" aria-hidden="true"></i>&nbsp;&nbsp;<span>Deactivate</span>', ['type' => 'button', 'class' => 'btn btn-warning pull-right margin-right-sm','id' => 'memDeactivateBtn','onclick' => 'memDeactivate('.$mem['id'].')']) !!}
        @else
          {!! Form::button('<i class="fa fa-check-square-o" aria-hidden="true"></i></i>&nbsp;&nbsp;<span>Reactivate</span>', ['type' => 'button', 'class' => 'btn bg-olive pull-right margin-right-sm','id' => 'memReactivateBtn','onclick' => 'memReactivate('.$mem['id'].')']) !!}
        @endif
      </div>
      <!-- /.box-footer -->
    {!! Form::close() !!}
  </div>
</section>
<!-- /.content -->
@endsection
