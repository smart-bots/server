<?php
  use SmartBots\Bot;
?>
@extends('hub.master')
@section('title','View schedule')
@section('additionHeader')
<link href="@asset('public/libs/multiselect/css/multi-select.css')" media="screen" rel="stylesheet" type="text/css">
<style>
  .action-item:first-child {
    padding: 6px 12px;
    border-radius: 5px;
    font-size: 15px;
    color: #fff;
  }

  .action-item {
    margin-top: 5px;
    padding: 6px 12px;
    border-radius: 5px;
    font-size: 15px;
    color: #fff;
  }

  .action-image {
    margin: 2px 5px;
    width: 40px;
    height: 40px;
    border: 2px solid #edf0f0;
  }

  .action-bot {
    font-weight: 600;
  }

  .cond-item:first-child {
    padding: 6px 12px;
    border-radius: 5px;
    font-size: 15px;
    color: #fff;
  }

  .cond-item {
    margin-top: 5px;
    padding: 6px 12px;
    border-radius: 5px;
    font-size: 15px;
    color: #fff;
  }

  .cond-image {
    margin: 2px 5px;
    width: 40px;
    height: 40px;
    border: 2px solid #edf0f0;
  }

  .condd {
    font-weight: 600;
  }

  .data {
    font-size: 16px;
  }

  .fre-item:first-child {
    padding: 6px 16px;
    border-radius: 5px;
    font-size: 15px;
    color: #555;
    background-color: #eee;
  }

  .fre-item {
    margin-top: 5px;
    padding: 6px 16px;
    border-radius: 5px;
    font-size: 15px;
    color: #555;
    background-color: #eee;
  }

  .fre-value, .fre-unit, .fre-at {
    font-weight: 600;
  }

  .deac-or {
    font-weight: 600;
  }

</style>
@endsection
@section('additionFooter')
<script src="@asset('public/libs/multiselect/js/jquery.multi-select.js')" type="text/javascript"></script>
<script src="@asset('public/libs/quicksearch/jquery.quicksearch.js')" type="text/javascript"></script>
<script>
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

  $(function () {
    $("[name='permissions[]']").multiSelect(searchableObj);
    $("[name='highpermissions[]']").multiSelect(searchableObj);
  });

  function scheduleDeactivate(id) {
    swal({
      title: "Are you sure?",
      text: "To deactivate this schedule?",
      type: "warning",
      showCancelButton: true,
      confirmButtonText: "Yes"}, function() {
        $.ajax({
            url : '{{ route('h::s::deactivate',$sche['id']) }}',
            type : 'post',
            dataType: 'json',
            data : {
              _token: '{{ csrf_token() }}',
              id: id
            },
            success : function (response)
            {
                $('#scheduleTus').text('Deactivated').removeClass('label-primary').addClass('label-danger');
                scheduleDeactivateBtn = $('#scheduleDeactivateBtn').attr('id','scheduleReactivateBtn').removeClass('btn-warning').addClass('btn-default').attr('onclick','scheduleReactivate()');
                scheduleDeactivateBtn.find('i').removeClass('fa-ban').addClass('fa-check-square-o');
                scheduleDeactivateBtn.find('span:not(.btn-label)').text('Reactivate');
            }
          });
      });
    }

  function scheduleReactivate(id) {
    swal({
      title: "Are you sure?",
      text: "To reactivate this schedule?",
      type: "warning",
      showCancelButton: true,
      confirmButtonText: "Yes"}, function() {
        $.ajax({
            url : '{{ route('h::s::reactivate',$sche['id']) }}',
            type : 'post',
            dataType: 'json',
            data : {
              _token: '{{ csrf_token() }}',
              id: id
            },
            success : function (response)
            {
                $('#scheduleTus').text('Activated').removeClass('label-danger').addClass('label-primary');
                scheduleReactivateBtn = $('#scheduleReactivateBtn').attr('id','scheduleDeactivateBtn').addClass('btn-warning').removeClass('btn-default').attr('onclick','scheduleDeactivate()');
                scheduleReactivateBtn.find('i').addClass('fa-ban').removeClass('fa-check-square-o');
                scheduleReactivateBtn.find('span:not(.btn-label)').text('Deactivate');
            }
          });
      });
  }

  function scheduleDelete() {
    swal({
      title: "Are you sure?",
      text: "To delete this schedule?",
      type: "warning",
      showCancelButton: true,
      confirmButtonText: "Yes",
      closeOnConfirm: false }, function() {
        $.ajax({
            url : '{!!  route('h::s::destroy',$sche['id']) !!}',
            type : 'post',
            dataType: 'json',
            data : { _token: '{{ csrf_token() }}' },
            success : function (response)
            {
                window.location.href = '{{ route('h::s::index') }}';
            }
          });
      });
  }

  function scheEdit() {
    $.ajax({
        url : '@route('h::s::edit',$sche['id'])',
        type : 'post',
        data : $('[name=sche-edit-form]').serializeArray(),
        dataType : 'json',
        success : function (response)
        {
            $('[name=sche-edit-form]').validate(response);
        }
    });
    return false;
  }
</script>
@endsection
@section('body')
{!! content_header('View schedule', [
    'Hub' => route('h::edit'),
    'Schedule' => route('h::s::index'),
    'View' => 'active']) !!}
<div class="row">
    <div class="col-sm-12">
        <div class="card-box">
    {!! Form::open(['route' => ['h::s::edit',$sche['id']], 'name' => 'sche-edit-form', 'class' => 'form-horizontal', 'onsubmit' => 'return scheEdit()']) !!}
      <div class="form-group">
        {!! Form::label('status', 'Status', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
          <?php
            switch ($sche['status']) {
              case 0:
                echo '<h4 class="m-t-5"><span class="label label-danger" id="scheduleTus">Deactivated</span></h4>';
                break;
              case 1:
                echo '<h4 class="m-t-5"><span class="label label-primary" id="scheduleTus">Activated</span></h4>';
                break;
            }
          ?>
        </div>
      </div>
      <div class="form-group">
        {!! Form::label('name', 'Name', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
          <p class="m-t-5 data">{{ $sche['name'] }}</p>
        </div>
      </div>
      @if (!empty($sche['description']))
      <div class="form-group">
        {!! Form::label('description', 'Description', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
          <p class="m-t-5 data">{{ $sche['description'] }}</p>
        </div>
      </div>
      @endif
      <div class="form-group">
        {!! Form::label('action', 'Action', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
        <?php
          $schedule_actions = explode('|',$sche->action);

          for($i=0; $i<count($schedule_actions); $i++) {

              $schedule_actions[$i] = explode(',',$schedule_actions[$i]);

              $bot = Bot::findOrFail($schedule_actions[$i][1]);
              switch ($schedule_actions[$i][0]) {
                case 1:
                  $action_class = 'bg-custom';
                  $action_text = 'Turn on';
                  break;
                case 2:
                  $action_class = 'bg-danger';
                  $action_text = 'Turn off';
                  break;
                case 3:
                  $action_class = 'bg-primary';
                  $action_text = 'Toggle';
                  break;
              }
              echo '<div class="action-item '.$action_class.'">
                <span class="action-type">'.$action_text.'</span>
                <img class="img-circle action-image" src="'.asset($bot->image).'"></img>
                <span class="action-bot">'.$bot->name.'<span>
              </div>';
          }
        ?>
        </div>
      </div>
      <div class="form-group">
        {!! Form::label('type', 'Type', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
          <p class="m-t-5 data"><?php
            switch ($sche['type']) {
              case 1:
                echo 'One time';
                break;
              case 2:
                echo 'Repeat';
                break;
          } ?></p>
        </div>
      </div>
      @if ($sche['type'] == 1)
      <div class="form-group">
        {!! Form::label('time', 'Time', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
          <p class="m-t-5 data">{{ $sche['data'] }}</p>
        </div>
      </div>
      @else
      <div class="form-group">
        {!! Form::label('frequency', 'Run', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
          <?php
          $data = explode('|',$sche['data']);
            foreach ($data as $single_data) {
              $single_data = explode(',',$single_data);
              echo '<div class="fre-item"><span>Every </span>
              <span class="fre-value">'.$single_data[1].' </span>';
              switch ($single_data[0]) {
                case 1;
                  $freUnit = 'minute(s)';
                  break;
                case 2;
                  $freUnit = 'hour(s)';
                  break;
                case 3;
                  $freUnit = 'day(s)';
                  break;
                case 4;
                  $freUnit = 'week(s)';
                  break;
                case 5;
                  $freUnit = 'month(s)';
                  break;
                case 6;
                  $freUnit = 'year(s)';
                  break;
              }
              echo '<span class="fre-unit">'.$freUnit.' </span>';
              if (isset($single_data[2]) && $single_data[2] != null) {
                echo "<span>at </span>";
                echo '<span class="fre-at">'.$single_data[2].'</span>';
              }
              echo '</div>';
            }
          ?>
        </div>
      </div>
      @endif
      @if ($sche->condition_type != 0)
        <?php
          switch ($sche->condition_type) {
            case 1:
              $condition_text = 'Work if';
              break;
            case 2:
              $condition_text = 'Not work if';
          }

          switch ($sche->condition_method) {
            case 1:
              $condition_text .= ' all true';
              break;
            case 2:
              $condition_text .= ' one true';
          }
        ?>
      <div class="form-group">
        {!! Form::label('condition', $condition_text, ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
          <?php
            $conditions = explode('|',$sche->condition_data);
            foreach ($conditions as $single_condition) {

              switch ($single_condition[1]) {
                case 0;
                  $condd = 'is turned off';
                  $cond_class = 'bg-danger';
                  break;
                case 1:
                  $condd = 'is turned on';
                  $cond_class = 'bg-custom';
                  break;
              }

              echo '<div class="cond-item '.$cond_class.'">';

              $single_condition = explode(',',$single_condition);
              $bot = Bot::findOrFail($single_condition[0]);

              echo '<img class="img-circle cond-image" src="'.asset($bot->image).'"></img>
              <span class="cond-bot">'.$bot->name.'</span>
              <span class="condd">'.$condd.'</span>';
              echo '</div>';
            }
          ?>
        </div>
      </div>
      @endif
      @if (!empty($sche['activate_after']))
      <div class="form-group">
        {!! Form::label('activate_after', 'Activate after', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
          <p class="m-t-5 data">{{ $sche['activate_after'] }}</p>
        </div>
      </div>
      @endif
      @if (!empty($sche['deactivate_after_times']) || !empty($sche['deactivate_after_datetime']))
      <div class="form-group">
        {!! Form::label('deactivate_after', 'Deactivate after', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
          <p class="m-t-5 data">
          @if (!empty($sche['deactivate_after_times']) && !empty($sche['deactivate_after_datetime']))
            {{ $sche['deactivate_after_times'] }} time(s)
            <span class="deac-or"> or </span>
            {{ $sche['deactivate_after_datetime'] }}
          @else
            @if (!empty($sche['deactivate_after_times']))
              {{ $sche['deactivate_after_times'] }}
            @else
              {{ $sche['deactivate_after_datetime'] }}
            @endif
          @endif
          </p>
        </div>
      </div>
      <div class="form-group">
        {!! Form::label('permissions', 'Low permissions', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
          {!! Form::select('permissions[]', $users, $selected, ['class' => 'form-control', 'multiple' => 'multiple']) !!}
          <span class="help-block margin-bottom-none">Users can manage this bot</span>
        </div>
      </div>
      <div class="form-group">
        {!! Form::label('highpermissions', 'High permissions', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
          {!! Form::select('highpermissions[]', $users, $selected2, ['class' => 'form-control', 'multiple' => 'multiple']) !!}
          <span class="help-block margin-bottom-none">Users can manage this bot</span>
        </div>
      </div>
      @endif
      {!! Form::button('<span class="btn-label"><i class="fa fa-floppy-o" aria-hidden="true"></i></span>Save', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
      {!! Form::button('<span class="btn-label"><i class="fa fa-trash" aria-hidden="true"></i></span>Delete', ['type' => 'button', 'class' => 'btn btn-danger pull-right', 'onclick' => 'scheduleDelete()']) !!}</a>
      @if ($sche['status'] != 0)
        {!! Form::button('<span class="btn-label"><i class="fa fa-ban" aria-hidden="true"></i></span><span>Deactivate</span>', ['type' => 'button', 'class' => 'btn btn-warning pull-right m-r-5','id' => 'scheduleDeactivateBtn','onclick' => 'scheduleDeactivate()']) !!}
      @else
        {!! Form::button('<span class="btn-label"><i class="fa fa-check-square-o" aria-hidden="true"></i></i></span><span>Reactivate</span>', ['type' => 'button', 'class' => 'btn btn-default pull-right m-r-5','id' => 'scheduleReactivateBtn','onclick' => 'scheduleReactivate()']) !!}
      @endif
    {!! Form::close() !!}
  </div>
</div>
</div>
@endsection
