@extends('hub.master')
@section('title','Add new member')
@section('additionHeader')
<link href="{{ asset('resources/assets/plugins/lou-multi-select/css/multi-select.css') }}" media="screen" rel="stylesheet" type="text/css">
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
</style>
@endsection
@section('additionFooter')
<script src="{{ asset('resources/assets/plugins/typeahead/typeahead.js') }}" type="text/javascript"></script>
<script src="{{ asset('resources/assets/plugins/handlebars/handlebars.js') }}" type="text/javascript"></script>
<script src="{{ asset('resources/assets/plugins/lou-multi-select/js/jquery.multi-select.js') }}" type="text/javascript"></script>
<script>
$("[name='permissions[]']").multiSelect();
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
