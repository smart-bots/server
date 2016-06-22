@extends('hub.master')
@section('title','Create automation')
@section('additionHeader')
  <link rel="stylesheet" href="@asset('public/libs/bootstrap-datetimepicker/bootstrap-datetimepicker.css')">
  <link href="@asset('public/libs/multiselect/css/multi-select.css')" media="screen" rel="stylesheet" type="text/css">
  <style>
    select {
      width: 125px !important;
    }
  </style>
@endsection
@section('additionFooter')
  <script src="@asset('public/libs/moment/moment.js')" type="text/javascript"></script>
  <script src="@asset('public/libs/moment/vi.js')" type="text/javascript"></script>
  <script src="@asset('public/libs/bootstrap-datetimepicker/bootstrap-datetimepicker.js')" type="text/javascript"></script>
  <script src="@asset('public/libs/typeahead.js/typeahead.bundle.js')" type="text/javascript"></script>
  <script src="@asset('public/libs/handlebars/handlebars.js')" type="text/javascript"></script>
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

    var event = new Bloodhound({
      datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
      queryTokenizer: Bloodhound.tokenizers.whitespace,
      remote: {
        url: '@route('h::e::search')/%Q/{{ session('currentHub') }}',
        wildcard: '%Q'
      }
    });

    var typeahead_event_option = {
      name: 'event',
      display: 'id',
      source: event,
      templates: {
        empty: [
        '<div class="tt-no-result">',
        'No result',
        '</div>'
        ].join(''),
        suggestion: Handlebars.compile([
          '<div class="">',
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
    $('[name="trigger[id]"]').typeahead(null, typeahead_bot_option);
    $('[name="action[bot][0]"]').typeahead(null, typeahead_bot_option);
    $('[name="condition[bot][0]"]').typeahead(null, typeahead_bot_option);
    $('.datetimepicker').datetimepicker({useCurrent: false, format: 'D-M-Y HH:mm'});

    function addAction() {
      var num = $('#actions').attr('data-count');

      $('#actions').attr('data-count',parseInt(num)+1);

      $('#actions').append([
        '<div class="input-group m-t-5">',
          '<div class="input-group-btn">',
            '<select class="form-control" style="margin-top: -5px" name="action[type]['+num+']"><option value="1">Toggle</option><option value="2">Turn on</option><option value="3">Turn off</option></select>',
          '</div>',
          '<input class="form-control b-left-0" name="action[bot]['+num+']" type="text">',
        '</div>'
        ].join(''));

      $('[name="action[bot]['+num+']"]').typeahead(null, typeahead_bot_option);
    }

    function changeCondition() {

      if ($('[name="condition[type]"]').val() != "0") {
        $('#conditions').html([
          '<div class="input-group m-t-10">',
            '{!! Form::text('condition[bot][0]', null, ['class' => 'form-control b-right-0']) !!}',
            '<div class="input-group-btn">',
              '{!! Form::select('condition[state][0]', ['0' => 'is turned on', '1' => 'is turned off'], null, ['class' => 'form-control','style' => 'margin-top: -5px;']) !!}',
            '</div>',
          '</div>'
        ].join('')).attr('data-count',1);

        $('#add-condition-btn').html('{!! Form::button('<span class="btn-label"><i class="fa fa-plus" aria-hidden="true"></i></span>Add condition', ['class' => 'btn btn-default pull-right m-t-5','onclick' => 'addCondition()']) !!}');

        $('#condMethod').html('{!! Form::select('condition[method]', ['1' => 'And', '2' => 'Or'], null, ['class' => 'form-control b-left-0']) !!}');

        $('[name="condition[bot][0]"]').typeahead(null, typeahead_bot_option);

      } else {

        $('#conditions').html('').attr('data-count',0);
        $('#add-condition-btn').html('');
        $('#condMethod').html('');

      }
    }

    function addCondition() {

      var num = $('#conditions').attr('data-count');

      $('#conditions').attr('data-count',parseInt(num)+1);

      $('#conditions').append([
          '<div class="input-group m-t-5">',
            '<input type="text" name="condition[bot]['+num+']" class="form-control b-right-0">',
            '<div class="input-group-btn">',
              '<select class="form-control" style="margin-top: -5px;" name="condition[state]['+num+']">',
                '<option value="0">is turned on</option>',
                '<option value="1">is turned off</option>',
              '</select>',
            '</div>',
          '</div>'
        ].join(''));

      $('[name="condition[bot]['+num+']"]').typeahead(null, typeahead_bot_option);
    }


    function changeTriggerType() {
      if ($('[name="trigger[type]"]').val() == "4") {
        $('[name="trigger[id]"]').typeahead('destroy').typeahead(null, typeahead_event_option);
      }
    }

    function automationCreate() {
      $.ajax({
          url : '@route('h::a::create')',
          type : 'post',
          data : $('[name=automation-create-form]').serializeArray(),
          dataType : 'json',
          success : function (response)
          {
              $('[name=automation-create-form]').validate(response, [], function () {
                window.location.href = response['href'];
              });
          }
      });
      return false;
    }
  </script>
@endsection
@section('body')
{!! content_header('Create new automation', [
    'Hub' => route('h::edit'),
    'Automation' => route('h::a::index'),
    'Create' => 'active'
]) !!}
<div class="row">
    <div class="col-sm-12">
        <div class="card-box">
            <h4 class="m-t-0 header-title"><b>Create new automation</b></h4>
            {!! Form::open(['route' => 'h::a::create', 'name' => 'automation-create-form', 'class' => 'form-horizontal', 'onsubmit' => 'return automationCreate()']) !!}
            <div class="form-group">
              {!! Form::label('name', 'Name', ['class' => 'col-sm-2 control-label']) !!}
              <div class="col-sm-10">
                {!! Form::text('name', old('name'), ['class' => 'form-control']) !!}
              </div>
            </div>
            <div class="form-group">
              {!! Form::label('description', 'Description', ['class' => 'col-sm-2 control-label']) !!}
              <div class="col-sm-10">
                {!! Form::textarea('description', old('description'), ['class' => 'form-control']) !!}
              </div>
            </div>
            <div class="form-group">
              {!! Form::label('trigger', 'Trigger', ['class' => 'col-sm-2 control-label']) !!}
              <div class="col-sm-10">
                <div class="input-group">
                  <div class="input-group-btn">
                    {!! Form::select('trigger[type]', ['1' => 'Bot toggled', '2' => 'Bot turned on', '3' => 'Bot turned off', '4' => 'Event fired'], null, ['class' => 'form-control', 'onchange' => 'changeTriggerType()', 'style' => 'margin-top: -5px']) !!}
                  </div>
                  {!! Form::text('trigger[id]', null, ['class' => 'form-control b-left-0']) !!}
                </div>
              </div>
            </div>
            <div class="form-group">
              {!! Form::label('action', 'Action', ['class' => 'col-sm-2 control-label']) !!}
              <div class="col-sm-10">
                <div id="actions" data-count="1">
                  <div class="input-group">
                    <div class="input-group-btn">
                      {!! Form::select('action[type][0]', ['1' => 'Toggle', '2' => 'Turn on', '3' => 'Turn off'], null, ['class' => 'form-control', 'style' => 'margin-top: -5px']) !!}
                    </div>
                    {!! Form::text('action[bot][0]', null, ['class' => 'form-control b-left-0']) !!}
                  </div>
                </div>
                {!! Form::button('<span class="btn-label"><i class="fa fa-plus" aria-hidden="true"></i></span>Add action', ['class' => 'btn btn-default pull-right','onclick' => 'addAction()']) !!}
              </div>
            </div>
            <div class="form-group">
              {!! Form::label('condition', 'Condition', ['class' => 'col-sm-2 control-label']) !!}
              <div class="col-sm-10">
                <div class="input-group">
                  {!! Form::select('condition[type]', ['0' => 'Off', '1' => 'Work if', '2' => 'Not work if'], null, ['class' => 'form-control', 'onChange' => 'changeCondition()']) !!}
                  <span id="condMethod"></span>
                </div>
                <div id='conditions' data-count="0"></div>
                <div id='add-condition-btn'></div>
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
