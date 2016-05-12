@extends('hub.master')
@section('title','Add new member')
@section('additionHeader')
<link href="{{ asset('resources/assets/plugins/lou-multi-select/css/multi-select.css') }}" media="screen" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="{{ asset('resources/assets/plugins/iCheck/square/blue.css') }}">
<style>
.tt-menu {
  width: 100%;
  border: 1px solid #3c8dbc;
  background-color: #fff;
  margin-top: -1px;
}

.tt-suggestion {
  padding: 3px 10px;
  width: 100%;
}

.tt-suggestion:hover {
  cursor: pointer;
  color: #fff;
  background-color: #3c8dbc;
}

.tt-suggestion.tt-cursor {
  cursor: pointer;
  color: #fff;
  background-color: #3c8dbc;
}

.tt-no-result {
  padding: 3px 10px;
  width: 100%;
}
.user-mini-ava {
  border-radius: 50%;
  width: 30px;
  margin: 5px;
}
.tt-text {
  margin-right: 5px;
}
.twitter-typeahead {
  width: 100%;
}
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
var username = new Bloodhound({
  datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
  queryTokenizer: Bloodhound.tokenizers.whitespace,
  remote: {
    url: '{{ route('a::search') }}/%Q',
    wildcard: '%Q'
  }
});

$('[name="username"]').typeahead(null, {
  name: 'username',
  display: 'username',
  source: username,
  templates: {
      empty: [
        '<div class="tt-no-result">',
          'No result',
        '</div>'
      ].join(''),
      suggestion: Handlebars.compile([
        '<table>',
        '<tr>',
          '<th rowspan="2" width="1%">',
            '<img class="user-mini-ava" src="@{{ avatar }}">',
          '</th>',
          '<th>',
            '<strong class="tt-text">@{{ username }}</strong>',
          '</th>',
        '</tr>',
        '<tr>',
        '<td>',
          '<span class="tt-text">@{{ name }}</span>',
        '</td>',
        '</tr>',
        '</table>'].join(''))
    }
});
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
    'Create' => 'active'
  ])}}
</section>
<!-- Main content -->
<section class="content">
  <!-- Default box -->
  <div class="box">
    <div class="box-header">
      <h3 class="box-title">Add new member</h3>
    </div>
    {!! Form::open(['route' => 'h::m::create', 'class' => 'form-horizontal']) !!}
      <div class="box-body">
        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
          {!! Form::label('username', 'Members\'s username', ['class' => 'col-sm-2 control-label']) !!}
          <div class="col-sm-10">
            {!! Form::text('username', old('username'), ['class' => 'form-control']) !!}
            @if ($errors->has('username'))
            <span class="help-block margin-bottom-none">
              {{ $errors->first('username') }}
            </span>
            @endif
          </div>
        </div>
        <div class="form-group{{ $errors->has('permissions') ? ' has-error' : '' }}">
          {!! Form::label('username', 'Members\'s permissions', ['class' => 'col-sm-2 control-label']) !!}
          <div class="col-sm-10">
            {!! Form::select('permissions[]', $bots, old('permissions'), ['class' => 'form-control', 'multiple' => 'multiple']) !!}
            @if ($errors->has('permissions'))
            <span class="help-block margin-bottom-none">
              {{ $errors->first('permissions') }}
            </span>
            @endif
          </div>
        </div>
        <div class="form-group{{ $errors->has('higherpermissions') ? ' has-error' : '' }}">
          {!! Form::label('username', 'Members\'s higher-permissions', ['class' => 'col-sm-2 control-label']) !!}
          <div class="col-sm-10">
            {!! Form::select('higherpermissions[]', $bots, old('higherpermissions'), ['class' => 'form-control', 'multiple' => 'multiple']) !!}
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
              <td>{!! Form::checkbox('hubpermissions[]', 1) !!}</td>
              <td>{!! Form::checkbox('hubpermissions[]', 2) !!}</td>
            </tr>
            <tr>
              <td class="text-left">Bots</td>
              <td>{!! Form::checkbox('hubpermissions[]', 3) !!}</td>
              <td>{!! Form::checkbox('hubpermissions[]', 4) !!}</td>
              <td>{!! Form::checkbox('hubpermissions[]', 5) !!}</td>
            </tr>
            <tr>
              <td class="text-left">Schedules</td>
              <td>{!! Form::checkbox('hubpermissions[]', 6) !!}</td>
              <td>{!! Form::checkbox('hubpermissions[]', 7) !!}</td>
              <td>{!! Form::checkbox('hubpermissions[]', 8) !!}</td>
            </tr>
            <tr>
              <td class="text-left">Automations</td>
              <td>{!! Form::checkbox('hubpermissions[]', 9) !!}</td>
              <td>{!! Form::checkbox('hubpermissions[]', 10) !!}</td>
              <td>{!! Form::checkbox('hubpermissions[]', 11) !!}</td>
            </tr>
            <tr>
              <td class="text-left">Members</td>
              <td>{!! Form::checkbox('hubpermissions[]', 12) !!}</td>
              <td>{!! Form::checkbox('hubpermissions[]', 13) !!}</td>
              <td>{!! Form::checkbox('hubpermissions[]', 14) !!}</td>
            </tr>
            </tbody>
            </table>
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
