@extends('hub.master')
@section('title','Add new bot')
@section('additionHeader')
  <link rel="stylesheet" href="{{ asset('public/libs/html5imageupload/html5imageupload.css') }}">
  <link href="{{ asset('public/libs/multiselect/css/multi-select.css') }}" media="screen" rel="stylesheet" type="text/css">
@endsection
@section('additionFooter')
  <script src="{{ asset('public/libs/html5imageupload/html5imageupload.js') }}"></script>
  <script src="{{ asset('public/libs/multiselect/js/jquery.multi-select.js') }}" type="text/javascript"></script>
  <script src="{{ asset('public/libs/quicksearch/jquery.quicksearch.js') }}" type="text/javascript"></script>
  <script>
    $('.dropzone').html5imageupload();

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
    $("[name='higherpermissions[]']").multiSelect(searchableObj);

    function verifyToken() {
      return false;
    }

    function botCreate() {
      $.ajax({
          url : '{{ route('h::b::create') }}',
          type : 'post',
          data : $('[name=bot-create-form]').serializeArray(),
          dataType : 'json',
          success : function (response)
          {
              $('[name=bot-create-form]').validate(response, [], function () {
                window.location.href = response['href'];
              });
          }
      });
      return false;
    }
  </script>
@endsection
@section('body')
  {!! content_header('Create bot', [
    'Hub' => route('h::edit'),
    'Bot' => route('h::b::index'),
    'Create' => 'active'
  ]) !!}
    <div class="row">
      <div class="col-sm-12">
        <div class="card-box">
          {!! Form::open(['route' => 'h::b::create', 'name' => 'bot-create-form', 'class' => 'form-horizontal', 'onsubmit' => 'return botCreate()']) !!}
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
            {!! Form::label('image', 'Image', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-10">
              <div class="dropzone image-dropzone" data-ghost="false" data-canvas="true" data-originalsize="false" data-ajax="false" data-width="200" data-height="200">
                {!! Form::file('image') !!}
              </div>
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('type', 'Type', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-10">
              {!! Form::select('type', [
                '1' => 'Flip-bot',
                '2' => 'Push-bot',
                '3' => 'Twist-bot',
                '4' => 'Plug-bot',
                '5' => 'Sense-bot',
                '6' => 'IR-bot'
                ], old('type'), ['class' => 'form-control']) !!}
              </div>
            </div>
            <div class="form-group">
              {!! Form::label('token', 'Token', ['class' => 'col-sm-2 control-label']) !!}
              <div class="col-sm-10">
                <div class="input-group">
                  {!! Form::text('token', old('token'), ['class' => 'form-control']) !!}
                  <span class="input-group-btn">
                    {!! Form::button('<i class="fa fa-bullseye" aria-hidden="true"></i>&nbsp;&nbsp;Verify', ['type' => 'button', 'class' => 'btn btn-default', 'onclick' => 'verifyToken();']) !!}
                  </span>
                </div>
              </div>
            </div>
            <div class="form-group{{ $errors->has('permissions') ? ' has-error' : '' }}">
              {!! Form::label('permissions', 'Permissions', ['class' => 'col-sm-2 control-label']) !!}
              <div class="col-sm-10">
                {!! Form::select('permissions[]', $users, old('permissions'), ['class' => 'form-control', 'multiple' => 'multiple']) !!}
                <span class="help-block margin-bottom-none">Users can view/control this bot</span>
              </div>
            </div>
            <div class="form-group{{ $errors->has('higherpermissions') ? ' has-error' : '' }}">
              {!! Form::label('higherpermissions', 'Higher permissions', ['class' => 'col-sm-2 control-label']) !!}
              <div class="col-sm-10">
                {!! Form::select('higherpermissions[]', $users, old('higherpermissions'), ['class' => 'form-control', 'multiple' => 'multiple']) !!}
                <span class="help-block margin-bottom-none">Users can edit/delete this bot</span>
              </div>
            </div>
            {!! Form::button('<span class="btn-label"><i class="fa fa-plus" aria-hidden="true"></i></span>Add', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
            {!! Form::close() !!}
          </div>
        </div>
      </div>
@endsection
