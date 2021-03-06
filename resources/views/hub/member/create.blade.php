<?php
  $title = "Add new member";
  $desciption = "";
?>
@extends('hub.master')
@section('title',$title)
@section('additionHeader')
<link href="@asset('public/libs/multiselect/css/multi-select.css')" media="screen" rel="stylesheet" type="text/css">

<style>
  .table td {
    font-weight: bold;
    text-align: center;
  }
</style>
@endsection
@section('additionFooter')
<script src="@asset('public/libs/multiselect/js/jquery.multi-select.js')" type="text/javascript"></script>
<script src="@asset('public/libs/quicksearch/jquery.quicksearch.js')" type="text/javascript"></script>
<script>
$("[name='hubpermissions[]']").materialSwitch();
var searchableObj = {
    selectableHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder='Search...'>",
    selectionHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder='Search...'>",
    afterInit: function (ms) {
        var that = this,
            $selectableSearch = that.$selectableUl.prev(),
            $selectionSearch = that.$selectionUl.prev(),
            selectableSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selectable:not(.ms-selected)',
            selectionSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selection.ms-selected';

        that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
            .on('keydown', function (e) {
                if (e.which === 40) {
                    that.$selectableUl.focus();
                    return false;
                }
            });

        that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
            .on('keydown', function (e) {
                if (e.which == 40) {
                    that.$selectionUl.focus();
                    return false;
                }
            });
    },
    afterSelect: function () {
        this.qs1.cache();
        this.qs2.cache();
    },
    afterDeselect: function () {
        this.qs1.cache();
        this.qs2.cache();
    }
};

$("[name^='permissions']").multiSelect(searchableObj);

var username = new Bloodhound({
  datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
  queryTokenizer: Bloodhound.tokenizers.whitespace,
  remote: {
    url: '@route('a::search')/%Q',
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
        '<div class="">',
          '<div class="pull-left">',
              '<img src="@{{ avatar }}" alt="" class="user-mini-ava">',
          '</div>',
          '<div>',
              '<strong>@{{ username }}</strong>',
              '<p class="m-0">@{{ name }}</p>',
          '</div>',
        '</div>'].join(''))
    }
});

function memberCreate() {
  $.ajax({
      url : '@route('h::m::create')',
      type : 'post',
      data : $('[name=member-create-form]').serializeArray(),
      dataType : 'json',
      success : function (response)
      {
          $('[name=member-create-form]').validate(response, [], function () {
              window.location.href = response['href'];
          });
      }
  });
  return false;
}
</script>
@endsection
@section('body')
@header($title, [
    'Hub' => route('h::edit'),
    'Member' => route('h::m::index'),
    'Create' => 'active'])
<div class="row">
    <div class="col-sm-12">
        <div class="card-box">
          {!! Form::open(['route' => 'h::m::create', 'name' => 'member-create-form', 'class' => 'form-horizontal', 'onsubmit' => 'return memberCreate()']) !!}
              <div class="form-group">
                {!! Form::label('username', 'Username', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                  {!! Form::text('username', null, ['class' => 'form-control']) !!}
                </div>
              </div>
              <div class="form-group">
                {!! Form::label('username', 'Bot low permissions', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                  {!! Form::select('permissions[bot][low][]', $bots, null, ['class' => 'form-control', 'multiple' => 'multiple']) !!}
                </div>
              </div>
              <div class="form-group">
                {!! Form::label('username', 'Bot high permissions', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                  {!! Form::select('permissions[bot][high][]', $bots, null, ['class' => 'form-control', 'multiple' => 'multiple']) !!}
                </div>
              </div>
              <div class="form-group">
                {!! Form::label('username', 'Schedule low permissions', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                  {!! Form::select('permissions[schedule][low][]', $schedules, null, ['class' => 'form-control', 'multiple' => 'multiple']) !!}
                </div>
              </div>
              <div class="form-group">
                {!! Form::label('username', 'Schedule high permissions', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                  {!! Form::select('permissions[schedule][high][]', $schedules, null, ['class' => 'form-control', 'multiple' => 'multiple']) !!}
                </div>
              </div>
              <div class="form-group">
                {!! Form::label('username', 'Event low permissions', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                  {!! Form::select('permissions[event][low][]', $events, null, ['class' => 'form-control', 'multiple' => 'multiple']) !!}
                </div>
              </div>
              <div class="form-group">
                {!! Form::label('username', 'Event high permissions', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                  {!! Form::select('permissions[event][high][]', $events, null, ['class' => 'form-control', 'multiple' => 'multiple']) !!}
                </div>
              </div>
              <div class="form-group">
                {!! Form::label('username', 'Automation low permissions', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                  {!! Form::select('permissions[automation][low][]', $automations, null, ['class' => 'form-control', 'multiple' => 'multiple']) !!}
                </div>
              </div>
              <div class="form-group">
                {!! Form::label('username', 'Automation high permissions', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                  {!! Form::select('permissions[automation][high][]', $automations, null, ['class' => 'form-control', 'multiple' => 'multiple']) !!}
                </div>
              </div>
              <div class="form-group" >
                <div class="col-sm-12 table-responsive">
                  <table class="table table-bordered table-striped">
                  <thead>
                    <tr>
                    <td></td>
                    <td width="1%">Add/Create</td>
                    <td width="1%">View/Control</td>
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
                    <td class="text-left">Events</td>
                    <td>{!! Form::checkbox('hubpermissions[]', 15) !!}</td>
                    <td>{!! Form::checkbox('hubpermissions[]', 16) !!}</td>
                    <td>{!! Form::checkbox('hubpermissions[]', 17) !!}</td>
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
              {!! Form::button('<span class="btn-label"><i class="fa fa-plus" aria-hidden="true"></i></span>Add', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
            <!-- /.box-footer -->
          {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
