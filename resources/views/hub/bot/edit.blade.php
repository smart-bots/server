@extends('hub.master')
@section('title','Edit bot')
@section('additionHeader')
  <link rel="stylesheet" href="@asset('public/libs/html5imageupload/html5imageupload.css')">
  <link href="@asset('public/libs/multiselect/css/multi-select.css')" media="screen" rel="stylesheet" type="text/css">
@endsection
@section('additionFooter')
<script src="@asset('public/libs/html5imageupload/html5imageupload.js')"></script>
<script src="@asset('public/libs/multiselect/js/jquery.multi-select.js')" type="text/javascript"></script>
<script src="@asset('public/libs/quicksearch/jquery.quicksearch.js')" type="text/javascript"></script>
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

  function botDeactivate() {
    swal({
      title: "Are you sure?",
      text: "To deactivate this bot?",
      type: "warning",
      showCancelButton: true,
      confirmButtonText: "Yes"}, function() {
          $.ajax({
              url : '@route('h::b::deactivate',$bot['id'])',
              type : 'post',
              dataType: 'json',
              data : { _token: '{{ csrf_token() }}' },
              success : function (response)
              {
                  $('#botTus').text('Deactivated').removeClass('label-default label-info label-success label-primary').addClass('label-danger');
                  botDeactivateBtn = $('#botDeactivateBtn').attr('id','botReactivateBtn').removeClass('btn-warning').addClass('btn-default').attr('onclick','botReactivate()');
                  botDeactivateBtn.find('i').removeClass('fa-ban').addClass('fa-check-square-o');
                  botDeactivateBtn.find('span:not(.btn-label)').text('Reactivate');
              }
            });
      });
  }

  function botReactivate() {
    swal({
        title: "Are you sure?",
        text: "To reactivate this bot?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes"}, function() {
            $.ajax({
                url : '@route('h::b::reactivate',$bot['id'])',
                type : 'post',
                dataType: 'json',
                data : { _token: '{{ csrf_token() }}' },
                success : function (response)
                {
                    $('#botTus').text('Reactivated').removeClass('label-default label-info label-success label-danger').addClass('label-primary');
                    botReactivateBtn = $('#botReactivateBtn').attr('id','botDeactivateBtn').addClass('btn-warning').removeClass('btn-default').attr('onclick','botDeactivate()');
                    botReactivateBtn.find('i').addClass('fa-ban').removeClass('fa-check-square-o');
                    botReactivateBtn.find('span:not(.btn-label)').text('Deactivate');
                }
              });
        });
  }

  function botDelete() {
    swal({
        title: "Are you sure?",
        text: "To delete this bot?",
        type: "error",
        showCancelButton: true,
        confirmButtonText: "Yes",
        closeOnConfirm: false }, function() {
            $.ajax({
                url : '@route('h::b::destroy',$bot['id'])',
                type : 'post',
                dataType: 'json',
                data : { _token: '{{ csrf_token() }}' },
                success : function (response)
                {
                    window.location.href = '@route('h::b::index')';
                }
              });
        });
  }

  function botEdit() {
    $.ajax({
        url : '@route('h::b::edit',$bot['id'])',
        type : 'post',
        data : $('[name=bot-edit-form]').serializeArray(),
        dataType : 'json',
        success : function (response)
        {
            $('[name=bot-edit-form]').validate(response);
        }
    });
    return false;
  }
</script>
@endsection
@section('body')
  @header('Edit bot', [
      'Hub' => route('h::edit'),
      'Bot' => route('h::b::index'),
      'Edit' => 'active'
  ])
  <div class="row">
    <div class="col-sm-12">
      <div class="card-box">
    {!! Form::open(['route' => ['h::b::edit',$bot['id']], 'name' => 'bot-edit-form', 'class' => 'form-horizontal', 'onsubmit' => 'return botEdit()']) !!}
      {!! Form::hidden('id', $bot['id']) !!}
      <div class="form-group margin-bottom-sm">
        {!! Form::label('status', 'Status',['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
          <?php
            switch ($bot['status']) {
              case -1:
                echo '<h4><span class="label label-danger label-md" id="botTus">Deactivated</span></h4>';
                break;
              case 0:
                if ($bot['true'] == 1) {
                  echo '<h4><span class="label label-default label-md" id="botTus">Turned off</span></h4>';
                } else {
                  echo '<h4><span class="label label-info label-md" id="botTus">Turning off</span></h4>';
                }
                break;
              case 1:
                if ($bot['true'] == 1) {
                  echo '<h4><span class="label label-success label-md" id="botTus">Turned on</span></h4>';
                } else {
                  echo '<h4><span class="label label-primary label-md" id="botTus">Turning on</span></h4>';
                }
                break;
            }
          ?>
        </div>
      </div>
      <div class="form-group">
        {!! Form::label('name', 'Name', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
          {!! Form::text('name', $bot['name'], ['class' => 'form-control']) !!}
        </div>
      </div>
      <div class="form-group">
        {!! Form::label('description', 'Description', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
          {!! Form::textarea('description', $bot['description'], ['class' => 'form-control']) !!}
        </div>
      </div>
      <div class="form-group">
        {!! Form::label('image', 'Image', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
          <div class="dropzone image-dropzone" data-image="@asset($bot['image'])" data-ghost="false" data-canvas="true" data-originalsize="false" data-ajax="false" data-width="200" data-height="200">
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
            ], $bot['type'], ['class' => 'form-control', 'readonly' => 'readonly']) !!}
          </div>
        </div>
        <div class="form-group">
          {!! Form::label('token', 'Token', ['class' => 'col-sm-2 control-label']) !!}
          <div class="col-sm-10">
            {!! Form::text('token', $bot['token'], ['class' => 'form-control','readonly' => 'readonly']) !!}
          </div>
        </div>
        <div class="form-group">
          {!! Form::label('permissions', 'Permissions', ['class' => 'col-sm-2 control-label']) !!}
          <div class="col-sm-10">
            {!! Form::select('permissions[]', $users, $selected, ['class' => 'form-control', 'multiple' => 'multiple']) !!}
            <span class="help-block margin-bottom-none">Users can manage this bot</span>
          </div>
        </div>
        <div class="form-group">
          {!! Form::label('higherpermissions', 'Higher permissions', ['class' => 'col-sm-2 control-label']) !!}
          <div class="col-sm-10">
            {!! Form::select('higherpermissions[]', $users, $selected2, ['class' => 'form-control', 'multiple' => 'multiple']) !!}
            <span class="help-block margin-bottom-none">Users can manage this bot</span>
          </div>
        </div>
        {!! Form::button('<span class="btn-label"><i class="fa fa-floppy-o" aria-hidden="true"></i></span>Save', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
        {!! Form::button('<span class="btn-label"><i class="fa fa-trash" aria-hidden="true"></i></span>Delete', ['type' => 'button', 'class' => 'btn btn-danger pull-right', 'onclick' => 'botDelete()']) !!}</a>
        @if ($bot['status'] != -1)
          {!! Form::button('<span class="btn-label"><i class="fa fa-ban" aria-hidden="true"></i></span><span>Deactivate</span>', ['type' => 'button', 'class' => 'btn btn-warning pull-right m-r-5','id' => 'botDeactivateBtn','onclick' => 'botDeactivate()']) !!}
        @else
          {!! Form::button('<span class="btn-label"><i class="fa fa-check-square-o" aria-hidden="true"></i></i></span><span>Reactivate</span>', ['type' => 'button', 'class' => 'btn btn-default pull-right m-r-5','id' => 'botReactivateBtn','onclick' => 'botReactivate()']) !!}
        @endif
    {!! Form::close() !!}
    </div>
  </div>
</div>
@endsection
