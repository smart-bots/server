<?php
  use SmartBots\Bot;
?>
@extends('hub.master')
@section('title',trans('hub/bot.edit_title'))
@section('additionHeader')
<link rel="stylesheet" href="@asset('public/libs/html5imageupload/html5imageupload.css')">
<link href="@asset('public/libs/multiselect/css/multi-select.css')" media="screen" rel="stylesheet" type="text/css">
@endsection
@section('additionFooter')
<script src="@asset('public/libs/html5imageupload/html5imageupload.js')"></script>
<script src="@asset('public/libs/multiselect/js/jquery.multi-select.js')" type="text/javascript"></script>
<script src="@asset('public/libs/quicksearch/jquery.quicksearch.js')" type="text/javascript"></script>
<script src="@asset('public/libs/highcharts/highstock.js')" type="text/javascript"></script>
<script>
  $('.dropzone').html5imageupload();

  var searchableObj = {
      selectableHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder='@trans('hub/bot.search')'>",
      selectionHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder='@trans('hub/bot.search')'>",
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

  function botDeactivate() {
    swal({
      title: "@trans('hub/bot.deactivate_title')",
      text: "@trans('hub/bot.deactivate_text')",
      type: "warning",
      showCancelButton: true,
      confirmButtonText: "@trans('hub/bot.deactivate_confirm')"}, function() {
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
        title: "@trans('hub/bot.reactivate_title')",
        text: "@trans('hub/bot.reactivate_text')",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "@trans('hub/bot.reactivate_confirm')"}, function() {
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
        title: "@trans('hub/bot.delete_tile')",
        text: "@trans('hub/bot.delete_text')",
        type: "error",
        showCancelButton: true,
        confirmButtonText: "@trans('hub/bot.delete_confirm')",
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

  // $(function () {
  //     $('#chart').highcharts('StockChart', {
  //       credits: {
  //           enabled: false
  //       },
  //       rangeSelector : {
  //           selected: 1
  //       },
  //       title: {
  //           text: 'Hourly power consumption',
  //           x: -20
  //       },
  //       subtitle: {
  //           text: 'of Plug-bot',
  //           x: -20
  //       },
  //       xAxis: {
  //           type: 'datetime',
  //           tickInterval: 3600 * 1000,
  //           min: Date.UTC(2016,4,22),
  //           max: Date.UTC(2016,4,23),
  //       },
  //       yAxis: {
  //           title: {
  //               text: 'Ampe'
  //           },
  //           plotLines: [{
  //               value: 0,
  //               width: 1,
  //               color: '#808080'
  //           }]
  //       },
  //       tooltip: {
  //           valueSuffix: 'A'
  //       },
  //       legend: {
  //           enabled: false,
  //           layout: 'vertical',
  //           align: 'center',
  //           verticalAlign: 'bottom',
  //           borderWidth: 0
  //       },
  //       series: [{
  //           name: 'Plug-bot',
  //           data: [7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6, 7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6],
  //           pointStart: Date.UTC(2016, 4, 22),
  //           pointInterval: 3600 * 1000,
  //           tooltip: {
  //               valueDecimals: 2
  //           }
  //       }]
  //   });
  // });
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
      {{-- <div id="chart" style="width:100%; height:400px;"></div> --}}
    {!! Form::open(['route' => ['h::b::edit',$bot['id']], 'name' => 'bot-edit-form', 'class' => 'form-horizontal', 'onsubmit' => 'return botEdit()']) !!}
      {!! Form::hidden('id', $bot['id']) !!}
      <div class="form-group margin-bottom-sm">
        {!! Form::label('status', 'Status',['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
          <?php
            switch ($bot['status']) {
              case -1:
                echo '<h4><span class="label label-danger label-md" id="botTus">'.trans('hub/bot.deactivate').'</span></h4>';
                break;
              case 0:
                if ($bot['true'] == 1) {
                  echo '<h4><span class="label label-default label-md" id="botTus">'.trans('hub/bot.turning_off').'</span></h4>';
                } else {
                  echo '<h4><span class="label label-info label-md" id="botTus">'.trans('hub/bot.turning_on').'</span></h4>';
                }
                break;
              case 1:
                if ($bot['true'] == 1) {
                  echo '<h4><span class="label label-success label-md" id="botTus">'.trans('hub/bot.turning_off').'</span></h4>';
                } else {
                  echo '<h4><span class="label label-primary label-md" id="botTus">'.trans('hub/bot.turning_on').'</span></h4>';
                }
                break;
            }
          ?>
        </div>
      </div>
      <div class="form-group">
        {!! Form::label('name', trans('hub/bot.name'), ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
          {!! Form::text('name', $bot['name'], ['class' => 'form-control']) !!}
        </div>
      </div>
      <div class="form-group">
        {!! Form::label('description', trans('hub/bot.description'), ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
          {!! Form::textarea('description', $bot['description'], ['class' => 'form-control']) !!}
        </div>
      </div>
      <div class="form-group">
        {!! Form::label('image', trans('hub/bot.image'), ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
          <div class="dropzone image-dropzone" data-image="@asset($bot['image'])" data-ghost="false" data-canvas="true" data-originalsize="false" data-ajax="false" data-width="200" data-height="200">
            {!! Form::file('image') !!}
          </div>
        </div>
      </div>
      <div class="form-group">
        {!! Form::label('type', trans('hub/bot.type'), ['class' => 'col-sm-2 control-label']) !!}
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
          {!! Form::label('token', trans('hub/bot.token'), ['class' => 'col-sm-2 control-label']) !!}
          <div class="col-sm-10">
            {!! Form::text('token', $bot['token'], ['class' => 'form-control','readonly' => 'readonly']) !!}
          </div>
        </div>
        <div class="form-group">
          {!! Form::label('notice', trans('hub/bot.get_notify'), ['class' => 'col-sm-2 control-label']) !!}
          <div class="col-sm-10">
            <div class="material-switch" style="margin-top:8px">
                <input id="notice" name="notice" type="checkbox" value="1" @if (auth()->user()->willNoticeByBot($bot['id'])) checked @endif/>
                <label for="notice" class="label-default0"></label>
            </div>
          </div>
        </div>
        <div class="form-group">
          {!! Form::label('permissions', trans('hub/bot.low_permissions'), ['class' => 'col-sm-2 control-label']) !!}
          <div class="col-sm-10">
            {!! Form::select('permissions[]', $users, $selected, ['class' => 'form-control', 'multiple' => 'multiple']) !!}
            <span class="help-block margin-bottom-none">@trans('hub/bot.low_perm_tip')</span>
          </div>
        </div>
        <div class="form-group">
          {!! Form::label('highpermissions', trans('hub/bot.high_permissions'), ['class' => 'col-sm-2 control-label']) !!}
          <div class="col-sm-10">
            {!! Form::select('highpermissions[]', $users, $selected2, ['class' => 'form-control', 'multiple' => 'multiple']) !!}
            <span class="help-block margin-bottom-none">@trans('hub/bot.high_perm_tip')</span>
          </div>
        </div>
        @if(auth()->user()->can('high',Bot::findOrFail($bot['id'])))
          {!! Form::button('<span class="btn-label"><i class="fa fa-floppy-o" aria-hidden="true"></i></span>'.trans('hub/bot.save'), ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
          {!! Form::button('<span class="btn-label"><i class="fa fa-trash" aria-hidden="true"></i></span>'.trans('hub/bot.delete'), ['type' => 'button', 'class' => 'btn btn-danger pull-right', 'onclick' => 'botDelete()']) !!}</a>
          @if ($bot['status'] != -1)
            {!! Form::button('<span class="btn-label"><i class="fa fa-ban" aria-hidden="true"></i></span><span>'.trans('hub/bot.deactivate').'</span>', ['type' => 'button', 'class' => 'btn btn-warning pull-right m-r-5','id' => 'botDeactivateBtn','onclick' => 'botDeactivate()']) !!}
          @else
            {!! Form::button('<span class="btn-label"><i class="fa fa-check-square-o" aria-hidden="true"></i></i></span><span>'.trans('hub/bot.reactivate').'</span>', ['type' => 'button', 'class' => 'btn btn-default pull-right m-r-5','id' => 'botReactivateBtn','onclick' => 'botReactivate()']) !!}
          @endif
        @endif
    {!! Form::close() !!}
    </div>
  </div>
</div>
@endsection
