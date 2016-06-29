<?php
  use SmartBots\{Automation, Bot, Event};
?>
@extends('hub.master')
@section('title','Edit automation')
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

  $("[name='permissions[]']").multiSelect(searchableObj);
  $("[name='highpermissions[]']").multiSelect(searchableObj);

  function automationDeactivate(id) {
    swal({
      title: "Are you sure?",
      text: "To deactivate this automation?",
      type: "warning",
      showCancelButton: true,
      confirmButtonText: "Yes"}, function() {
        $.ajax({
            url : '{{ route('h::a::deactivate',$automation['id']) }}',
            type : 'post',
            dataType: 'json',
            data : {
              _token: '{{ csrf_token() }}',
              id: id
            },
            success : function (response)
            {
                $('#automationTus').text('Deactivated').removeClass('label-primary').addClass('label-danger');
                automationDeactivateBtn = $('#automationDeactivateBtn').attr('id','automationReactivateBtn').removeClass('btn-warning').addClass('btn-default').attr('onclick','automationReactivate()');
                automationDeactivateBtn.find('i').removeClass('fa-ban').addClass('fa-check-square-o');
                automationDeactivateBtn.find('span:not(.btn-label)').text('Reactivate');
            }
          });
      });
    }

  function automationReactivate(id) {
    swal({
      title: "Are you sure?",
      text: "To reactivate this automation?",
      type: "warning",
      showCancelButton: true,
      confirmButtonText: "Yes"}, function() {
        $.ajax({
            url : '{{ route('h::a::reactivate',$automation['id']) }}',
            type : 'post',
            dataType: 'json',
            data : {
              _token: '{{ csrf_token() }}',
              id: id
            },
            success : function (response)
            {
                $('#automationTus').text('Activated').removeClass('label-danger').addClass('label-primary');
                automationReactivateBtn = $('#automationReactivateBtn').attr('id','automationDeactivateBtn').addClass('btn-warning').removeClass('btn-default').attr('onclick','automationDeactivate()');
                automationReactivateBtn.find('i').addClass('fa-ban').removeClass('fa-check-square-o');
                automationReactivateBtn.find('span:not(.btn-label)').text('Deactivate');
            }
          });
      });
  }

  function automationDelete() {
    swal({
      title: "Are you sure?",
      text: "To delete this automation?",
      type: "warning",
      showCancelButton: true,
      confirmButtonText: "Yes",
      closeOnConfirm: false }, function() {
        $.ajax({
            url : '{!!  route('h::a::destroy',$automation['id']) !!}',
            type : 'post',
            dataType: 'json',
            data : { _token: '{{ csrf_token() }}' },
            success : function (response)
            {
                window.location.href = '{{ route('h::a::index') }}';
            }
          });
      });
  }

  function automationEdit() {
    $.ajax({
        url : '@route('h::a::edit',$automation['id'])',
        type : 'post',
        data : $('[name=automation-edit-form]').serializeArray(),
        dataType : 'json',
        success : function (response)
        {
            $('[name=automation-edit-form]').validate(response);
        }
    });
    return false;
  }
</script>
@endsection
@section('body')
{!! content_header('Edit automation', [
    'Hub' => route('h::edit'),
    'Edit' => route('h::a::index'),
    'Create' => 'active'
]) !!}
<div class="row">
    <div class="col-sm-12">
        <div class="card-box">
            <h4 class="m-t-0 header-title"><b>Edit automation</b></h4>
            {!! Form::open(['route' => ['h::a::edit',$automation['id']], 'name' => 'automation-edit-form', 'class' => 'form-horizontal', 'onsubmit' => 'return automationEdit()']) !!}
            <div class="form-group">
              {!! Form::label('status', 'Status', ['class' => 'col-sm-2 control-label']) !!}
              <div class="col-sm-10">
                <?php
                  switch ($automation['status']) {
                    case 0:
                      echo '<h4 class="m-t-5"><span class="label label-danger" id="automationTus">Deactivated</span></h4>';
                      break;
                    case 1:
                      echo '<h4 class="m-t-5"><span class="label label-primary" id="automationTus">Activated</span></h4>';
                      break;
                  }
                ?>
              </div>
            </div>
            <div class="form-group">
              {!! Form::label('name', 'Name', ['class' => 'col-sm-2 control-label']) !!}
              <div class="col-sm-10">
                {!! Form::text('name', $automation['name'], ['class' => 'form-control']) !!}
              </div>
            </div>
            <div class="form-group">
              {!! Form::label('description', 'Description', ['class' => 'col-sm-2 control-label']) !!}
              <div class="col-sm-10">
                {!! Form::textarea('description', $automation['description'], ['class' => 'form-control']) !!}
              </div>
            </div>
            <div class="form-group">
              {!! Form::label('trigger', 'Trigger', ['class' => 'col-sm-2 control-label']) !!}
              <div class="col-sm-10">
                <?php
                  switch ($automation->trigger_type) {
                    case 1:
                      $bot = Bot::findOrFail($automation->trigger_id);
                      $trigger_class = 'bg-custom';
                      $trigger_text = 'turned on';
                      $trigger_image = $bot->image;
                      $trigger_name = $bot->name;
                      break;
                    case 2:
                      $bot = Bot::findOrFail($automation->trigger_id);
                      $trigger_class = 'bg-danger';
                      $trigger_text = 'turned off';
                      $trigger_image = $bot->image;
                      $trigger_name = $bot->name;
                      break;
                    case 3:
                      $bot = Bot::findOrFail($automation->trigger_id);
                      $trigger_class = 'bg-primary';
                      $trigger_text = 'toggled';
                      $trigger_image = $bot->image;
                      $trigger_name = $bot->name;
                      break;
                    case 4:
                      $event = Event::findOrFail($automation->trigger_id);
                      $trigger_class = 'bg-warning';
                      $trigger_text = 'fired';
                      $trigger_name = $event->name;
                      break;
                  }
                  echo '<div class="action-item '.$trigger_class.'">';
                  if (isset($trigger_image)) {
                    echo '<img class="img-circle action-image" src="'.asset($trigger_image).'"></img>';
                  }
                  echo '<span class="action-bot">'.$trigger_name.'</span>
                      <span class="action-type">'.$trigger_text.'</span>
                    </div>';
                ?>
              </div>
            </div>
            <div class="form-group">
              {!! Form::label('action', 'Action', ['class' => 'col-sm-2 control-label']) !!}
              <div class="col-sm-10">
              <?php
                $automation_actions = explode('|',$automation->action);

                for($i=0; $i<count($automation_actions); $i++) {

                    $automation_actions[$i] = explode(',',$automation_actions[$i]);

                    $bot = Bot::findOrFail($automation_actions[$i][1]);
                    switch ($automation_actions[$i][0]) {
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
                      <span class="action-bot">'.$bot->name.'</span>
                    </div>';
                }
              ?>
              </div>
            </div>
            @if ($automation->condition_type != 0)
                    <?php
                      switch ($automation->condition_type) {
                        case 1:
                          $condition_text = 'Work if';
                          break;
                        case 2:
                          $condition_text = 'Not work if';
                      }

                      switch ($automation->condition_method) {
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
                        $conditions = explode('|',$automation->condition_data);
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
            <div class="form-group">
              {!! Form::label('notice', 'Get notify', ['class' => 'col-sm-2 control-label']) !!}
              <div class="col-sm-10">
                <div class="material-switch" style="margin-top:8px">
                    <input id="notice" name="notice" type="checkbox" value="1" @if (auth()->user()->willNoticeByAutomation($automation['id'])) checked @endif/>
                    <label for="notice" class="label-default"></label>
                </div>
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
            @if(auth()->user()->can('high',Automation::findOrFail($automation['id'])))
              {!! Form::button('<span class="btn-label"><i class="fa fa-floppy-o" aria-hidden="true"></i></span>Save', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
              {!! Form::button('<span class="btn-label"><i class="fa fa-trash" aria-hidden="true"></i></span>Delete', ['type' => 'button', 'class' => 'btn btn-danger pull-right', 'onclick' => 'automationDelete()']) !!}</a>
              @if ($automation['status'] != 0)
                {!! Form::button('<span class="btn-label"><i class="fa fa-ban" aria-hidden="true"></i></span><span>Deactivate</span>', ['type' => 'button', 'class' => 'btn btn-warning pull-right m-r-5','id' => 'automationDeactivateBtn','onclick' => 'automationDeactivate()']) !!}
              @else
                {!! Form::button('<span class="btn-label"><i class="fa fa-check-square-o" aria-hidden="true"></i></i></span><span>Reactivate</span>', ['type' => 'button', 'class' => 'btn btn-default pull-right m-r-5','id' => 'automationReactivateBtn','onclick' => 'automationReactivate()']) !!}
              @endif
              {!! Form::close() !!}
            @endif
        </div>
    </div>
</div>
@endsection
