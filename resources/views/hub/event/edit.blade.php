@extends('hub.master')
@section('title','Edit event')
@section('additionHeader')
  <link href="@asset('public/libs/multiselect/css/multi-select.css')" media="screen" rel="stylesheet" type="text/css">
  <style>
    select {
      width: 125px !important;
    }
  </style>
@endsection
@section('additionFooter')
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

    function eventDeactivate(id) {
      swal({
        title: "Are you sure?",
        text: "To deactivate this event?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes"}, function() {
          $.ajax({
              url : '{{ route('h::e::deactivate',$event['id']) }}',
              type : 'post',
              dataType: 'json',
              data : {
                _token: '{{ csrf_token() }}',
                id: id
              },
              success : function (response)
              {
                  $('#eventTus').text('Deactivated').removeClass('label-primary').addClass('label-danger');
                  eventDeactivateBtn = $('#eventDeactivateBtn').attr('id','eventReactivateBtn').removeClass('btn-warning').addClass('btn-default').attr('onclick','eventReactivate()');
                  eventDeactivateBtn.find('i').removeClass('fa-ban').addClass('fa-check-square-o');
                  eventDeactivateBtn.find('span:not(.btn-label)').text('Reactivate');
              }
            });
        });
      }

    function eventReactivate(id) {
      swal({
        title: "Are you sure?",
        text: "To reactivate this event?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes"}, function() {
          $.ajax({
              url : '{{ route('h::e::reactivate',$event['id']) }}',
              type : 'post',
              dataType: 'json',
              data : {
                _token: '{{ csrf_token() }}',
                id: id
              },
              success : function (response)
              {
                  $('#eventTus').text('Activated').removeClass('label-danger').addClass('label-primary');
                  eventReactivateBtn = $('#eventReactivateBtn').attr('id','eventDeactivateBtn').addClass('btn-warning').removeClass('btn-default').attr('onclick','eventDeactivate()');
                  eventReactivateBtn.find('i').addClass('fa-ban').removeClass('fa-check-square-o');
                  eventReactivateBtn.find('span:not(.btn-label)').text('Deactivate');
              }
            });
        });
    }

    function eventDelete() {
      swal({
        title: "Are you sure?",
        text: "To delete this event?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes",
        closeOnConfirm: false }, function() {
          $.ajax({
              url : '{!!  route('h::e::destroy',$event['id']) !!}',
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

    function eventEdit() {
      $.ajax({
          url : '@route('h::e::edit',$event['id'])',
          type : 'post',
          data : $('[name=event-edit-form]').serializeArray(),
          dataType : 'json',
          success : function (response)
          {
              $('[name=event-edit-form]').validate(response);
          }
      });
      return false;
    }
  </script>
@endsection
@section('body')
{!! content_header('Edit event', [
    'Hub' => route('h::edit'),
    'Event' => route('h::e::index'),
    'Edit' => 'active'
]) !!}
<div class="row">
    <div class="col-sm-12">
        <div class="card-box">
            <h4 class="m-t-0 header-title"><b>Edit event</b></h4>
            {!! Form::open(['route' => ['h::e::edit',$event['id']], 'name' => 'event-edit-form', 'class' => 'form-horizontal', 'onsubmit' => 'return eventEdit()']) !!}
            <div class="form-group">
              {!! Form::label('status', 'Status', ['class' => 'col-sm-2 control-label']) !!}
              <div class="col-sm-10">
                <?php
                  switch ($event['status']) {
                    case 0:
                      echo '<h4 class="m-t-5"><span class="label label-danger" id="eventTus">Deactivated</span></h4>';
                      break;
                    case 1:
                      echo '<h4 class="m-t-5"><span class="label label-primary" id="eventTus">Activated</span></h4>';
                      break;
                  }
                ?>
              </div>
            </div>
            <div class="form-group">
              {!! Form::label('name', 'Name', ['class' => 'col-sm-2 control-label']) !!}
              <div class="col-sm-10">
                {!! Form::text('name', $event['name'], ['class' => 'form-control']) !!}
              </div>
            </div>
            @if(!empty($automation->trigger_id))
            <div class="form-group">
              {!! Form::label('trigger', 'Trigger', ['class' => 'col-sm-2 control-label']) !!}
              <div class="col-sm-10">
                <?php
                  switch ($event->trigger_type) {
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
                  }
                  echo '<div class="action-item '.$trigger_class.'">
                      <img class="img-circle action-image" src="'.asset($trigger_image).'"></img>
                      <span class="action-bot">'.$trigger_name.'</span>
                      <span class="action-type">'.$trigger_text.'</span>
                    </div>';
                ?>
              </div>
            </div>
            @endif
            <div class="form-group">
              {!! Form::label('permissions', 'Low permissions', ['class' => 'col-sm-2 control-label']) !!}
              <div class="col-sm-10">
                {!! Form::select('permissions[]', $users, $selected, ['class' => 'form-control', 'multiple' => 'multiple']) !!}
                <span class="help-block margin-bottom-none">Users can view/control this bot</span>
              </div>
            </div>
            <div class="form-group">
              {!! Form::label('highpermissions', 'High permissions', ['class' => 'col-sm-2 control-label']) !!}
              <div class="col-sm-10">
                {!! Form::select('highpermissions[]', $users, $selected2, ['class' => 'form-control', 'multiple' => 'multiple']) !!}
                <span class="help-block margin-bottom-none">Users can edit/delete this bot</span>
              </div>
            </div>
            {!! Form::button('<span class="btn-label"><i class="fa fa-floppy-o" aria-hidden="true"></i></span>Save', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
            {!! Form::button('<span class="btn-label"><i class="fa fa-trash" aria-hidden="true"></i></span>Delete', ['type' => 'button', 'class' => 'btn btn-danger pull-right', 'onclick' => 'eventDelete()']) !!}</a>
            @if ($event['status'] != -1)
              {!! Form::button('<span class="btn-label"><i class="fa fa-ban" aria-hidden="true"></i></span><span>Deactivate</span>', ['type' => 'button', 'class' => 'btn btn-warning pull-right m-r-5','id' => 'eventDeactivateBtn','onclick' => 'eventDeactivate()']) !!}
            @else
              {!! Form::button('<span class="btn-label"><i class="fa fa-check-square-o" aria-hidden="true"></i></i></span><span>Reactivate</span>', ['type' => 'button', 'class' => 'btn btn-default pull-right m-r-5','id' => 'eventReactivateBtn','onclick' => 'eventReactivate()']) !!}
            @endif
        {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
