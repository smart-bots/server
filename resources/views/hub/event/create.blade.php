@extends('hub.master')
@section('title','Create event')
@section('additionHeader')
<link href="@asset('public/libs/multiselect/css/multi-select.css')" media="screen" rel="stylesheet" type="text/css">
<style>
  select {
    width: 125px !important;
  }
</style>
@endsection
@section('additionFooter')
<script src="@asset('public/libs/multiselect/js/jquery.multi-select.js')" type="text/javascript"></script>
<script src="@asset('public/libs/quicksearch/jquery.quicksearch.js')" type="text/javascript"></script>
<script>

  var bot = new Bloodhound({
    datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    remote: {
      url: '@route('h::b::search')/%Q/{{ session('currentHub') }}',
      wildcard: '%Q'
    }
  });

  var typeahead_bot_option = {
    name: 'bot',
    display: 'id',
    source: bot,
    templates: {
      empty: [
      '<div class="tt-no-result">',
      'No result',
      '</div>'
      ].join(''),
      suggestion: Handlebars.compile([
        '<div class="">',
          '<div class="pull-left">',
              '<img src="@{{ image }}" alt="" class="user-mini-ava">',
          '</div>',
          '<div>',
              '<strong>@{{ name }}</strong>',
              '<p class="m-0">@{{ id }}</p>',
          '</div>',
        '</div>'].join(''))
    }
  };

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

  $("[name='permissions[]']").multiSelect(searchableObj);
  $("[name='highpermissions[]']").multiSelect(searchableObj);
  $('[name="trigger[bot]"]').typeahead(null, typeahead_bot_option);

  function eventCreate() {
    $.ajax({
        url : '@route('h::e::create')',
        type : 'post',
        data : $('[name=event-create-form]').serializeArray(),
        dataType : 'json',
        success : function (response)
        {
            $('[name=event-create-form]').validate(response, [], function () {
              window.location.href = response['href'];
            });
        }
    });
    return false;
  }
</script>
@endsection
@section('body')
{!! content_header('Create new event', [
    'Hub' => route('h::edit'),
    'Event' => route('h::e::index'),
    'Create' => 'active'
]) !!}
<div class="row">
    <div class="col-sm-12">
        <div class="card-box">
            <h4 class="m-t-0 header-title"><b>Create new event</b></h4>
            {!! Form::open(['route' => 'h::e::create', 'name' => 'event-create-form', 'class' => 'form-horizontal', 'onsubmit' => 'return eventCreate()']) !!}
            <div class="form-group">
              {!! Form::label('name', 'Name', ['class' => 'col-sm-2 control-label']) !!}
              <div class="col-sm-10">
                {!! Form::text('name', old('name'), ['class' => 'form-control']) !!}
              </div>
            </div>
            <div class="form-group">
              {!! Form::label('action', 'Action', ['class' => 'col-sm-2 control-label']) !!}
              <div class="col-sm-10">
                <div class="input-group">
                  <div class="input-group-btn">
                    {!! Form::select('trigger[type]', ['1' => 'Toggle', '2' => 'Turn on', '3' => 'Turn off'], null, ['class' => 'form-control', 'style' => 'margin-top: -5px']) !!}
                  </div>
                  {!! Form::text('trigger[bot]', null, ['class' => 'form-control b-left-0']) !!}
                </div>
              </div>
            </div>
            <div class="form-group">
              {!! Form::label('notice', 'Get notify', ['class' => 'col-sm-2 control-label']) !!}
              <div class="col-sm-10">
                <div class="material-switch" style="margin-top:8px">
                    <input id="notice" name="notice" type="checkbox" value="1"/>
                    <label for="notice" class="label-default"></label>
                </div>
              </div>
            </div>
            <div class="form-group">
              {!! Form::label('permissions', 'Low permissions', ['class' => 'col-sm-2 control-label']) !!}
              <div class="col-sm-10">
                {!! Form::select('permissions[]', $users, old('permissions'), ['class' => 'form-control', 'multiple' => 'multiple']) !!}
                <span class="help-block margin-bottom-none">Users can view/control this bot</span>
              </div>
            </div>
            <div class="form-group">
              {!! Form::label('highpermissions', 'High permissions', ['class' => 'col-sm-2 control-label']) !!}
              <div class="col-sm-10">
                {!! Form::select('highpermissions[]', $users, old('highpermissions'), ['class' => 'form-control', 'multiple' => 'multiple']) !!}
                <span class="help-block margin-bottom-none">Users can edit/delete this bot</span>
              </div>
            </div>
            {!! Form::button('<span class="btn-label"><i class="fa fa-plus" aria-hidden="true"></i></span>Add', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
          {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
