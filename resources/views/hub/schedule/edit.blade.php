@extends('hub.master')
@section('title','Edit schedule')
@section('additionHeader')
<link rel="stylesheet" href="{{ asset('public/libs/bootstrap-datetimepicker/bootstrap-datetimepicker.css') }}">
<style>
  select {
    width: 125px !important;
  }
</style>
@endsection
@section('additionFooter')
<script src="{{ asset('public/libs/typeahead.js/typeahead.bundle.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/libs/handlebars/handlebars.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/libs/moment/moment.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/libs/bootstrap-datetimepicker/bootstrap-datetimepicker.js') }}" type="text/javascript"></script>
<script>
  var bot = new Bloodhound({
    datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    remote: {
      url: '{{ route('h::b::search') }}/%Q/{{ session('currentHub') }}',
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

  $(function () {
    $('[name="action[bot][0]"]').typeahead(null, typeahead_bot_option);
    $('[name="condition[bot][0]"]').typeahead(null, typeahead_bot_option);
    $('.datetimepicker').datetimepicker({useCurrent: false});
  });

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

  function changeType() {

    if ($('[name=type]').val() == '1') {
      $('#data').html([
        '<div class="form-group">',
          '{!! Form::label('time', 'Time', ['class' => 'col-sm-2 control-label']) !!}',
          '<div class="col-sm-10">',
            '{!! Form::text('time', old('time'), ['class' => 'form-control datetimepicker']) !!}',
          '</div>',
        '</div>'
      ].join(''));

    } else {

      $('#data').html([
        '<div class="form-group">',
          '{!! Form::label('frequency', 'Frequency', ['class' => 'col-sm-2 control-label']) !!}',
          '<div class="col-sm-10">',
            '<div id="frequencies" data-count="1" class="m-b-5">',
              '<div class="input-group">',
                '<span class="input-group-addon">Every</span>',
                '{!! Form::text('frequency[value][0]', 1, ['class' => 'form-control b-right-0']) !!}',
                '<div class="input-group-btn">',
                  '{!! Form::select('frequency[unit][0]', ['1' => 'minute(s)', '2' => 'hour(s)', '3' => 'day(s)', '4' => 'week(s)', '5' => 'month(s)', '6' => 'year(s)'], null, ['class' => 'form-control', 'onChange' => 'changeFrequency(0)']) !!}',
                '</div>',
                '<span class="input-group-addon b-left-0 b-right-0" id="fre-at-text-0" style="display: none">At</span>',
                '{!! Form::text('frequency[at][0]', null, ['id' => 'fre-at-input-0', 'class' => 'form-control datetimepicker', 'readonly' => 'readonly', 'style' => 'display: none']) !!}',
              '</div>',
            '</div>',
          '{!! Form::button('<span class="btn-label"><i class="fa fa-plus" aria-hidden="true"></i></span>Add frequency', ['class' => 'btn btn-default pull-right', 'onclick' => 'addFrequency()']) !!}',
          '</div>',
        '</div>'
      ].join(''));

    }

    $('.datetimepicker').datetimepicker({useCurrent: false});
  }

  function changeFrequency(id) {
    var dtpicker = $('[name="frequency[at]['+id+']"]');
    switch ($('[name="frequency[unit]['+id+']"]').val()) {
      case "1":
        dtpicker.attr('readonly','readonly').val('');
        $('#fre-at-text-'+id).hide();
        $('#fre-at-input-'+id).hide();
        break;
      case "2":
        dtpicker.removeAttr('readonly').data("DateTimePicker").format('mm');
        $('#fre-at-text-'+id).show();
        $('#fre-at-input-'+id).show();
        break;
      case "3":
        dtpicker.removeAttr('readonly').data("DateTimePicker").format('HH:mm');
        $('#fre-at-text-'+id).show();
        $('#fre-at-input-'+id).show();
        break;
      case "4":
        dtpicker.removeAttr('readonly').data("DateTimePicker").format('dddd HH:mm');
        $('#fre-at-text-'+id).show();
        $('#fre-at-input-'+id).show();
        break;
      case "5":
        dtpicker.removeAttr('readonly').data("DateTimePicker").format('D HH:mm');
        $('#fre-at-text-'+id).show();
        $('#fre-at-input-'+id).show();
        break;
      case "6":
        dtpicker.removeAttr('readonly').data("DateTimePicker").format('MMMM D HH:mm');
        $('#fre-at-text-'+id).show();
        $('#fre-at-input-'+id).show();
        break;
    }
  }

  function addFrequency() {
    var num = $('#frequencies').attr('data-count');

    $('#frequencies').attr('data-count',parseInt(num)+1);

    $('#frequencies').append([
      '<div class="input-group m-t-10">',
        '<span class="input-group-addon">Every</span>',
        '<input class="form-control b-right-0" name="frequency[value]['+num+']" type="text" value="1">',
        '<div class="input-group-btn">',
          '<select class="form-control" onchange="changeFrequency('+num+')" name="frequency[unit]['+num+']">',
            '<option value="1">minute(s)</option>',
            '<option value="2">hour(s)</option>',
            '<option value="3">day(s)</option>',
            '<option value="4">week(s)</option>',
            '<option value="5">month(s)</option>',
            '<option value="6">year(s)</option>',
          '</select>',
        '</div>',
        '<span class="input-group-addon b-left-0 b-right-0" id="fre-at-text-'+num+'" style="display: none">At</span>',
        '<input class="form-control datetimepicker" readonly name="frequency[at]['+num+']" type="text" id="fre-at-input-'+num+'" style="display: none">',
      '</div>',
      ].join(''));

    $('.datetimepicker').datetimepicker({useCurrent: false});
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
      ].join(''));

      $('#add-condition-btn').html('{!! Form::button('<span class="btn-label"><i class="fa fa-plus" aria-hidden="true"></i></span>Add condition', ['class' => 'btn btn-default pull-right m-t-5','onclick' => 'addCondition()']) !!}');

      $('#condMethod').html('{!! Form::select('condition[method]', ['1' => 'And', '2' => 'Or'], null, ['class' => 'form-control b-left-0']) !!}');

      $('[name="condition[bot][0]"]').typeahead(null, typeahead_bot_option);

    } else {

      $('#conditions').html('');
      $('#add-condition-btn').html('');
      $('#condMethod').html('');

    }
  }

  function addCondition() {

    $('#conditions').append([
        '<div class="input-group m-t-5">',
          '{!! Form::text('condition[bot][0]', null, ['class' => 'form-control b-right-0']) !!}',
          '<div class="input-group-btn">',
            '{!! Form::select('condition[state][0]', ['0' => 'is turned on', '1' => 'is turned off'], null, ['class' => 'form-control','style' => 'margin-top: -5px;']) !!}',
          '</div>',
        '</div>'
      ].join(''));

    $('[name="condition[bot][0]"]').typeahead(null, typeahead_bot_option);
  }

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

  function createSchedule() {

    // var i = 0;
    // var action_types = $('[name="action[type][0]"]').toArray();
    // action_types.forEach( function(element, index) {
    //   $(element).attr('name','action[type]['+i+']');
    //   i++;
    // });

    $.ajax({
      url : '{{ route('h::s::create') }}',
      type : 'post',
      data : $('[name=create-schedule-form]').serializeArray(),
      dataType : 'json',
      success : function (response)
      {
        var response2 = {};
        for (var prop in response) {
            if (prop.indexOf('.') != -1) {
                var propArr = prop.split('.');
                var prop2 = propArr[0]+'['+propArr[1]+']['+propArr[2]+']';
                response2[prop2] = response[prop];
            } else {
                response2[prop] = response[prop];
            }
        }
        response = response2;

        var action_inputs = $('[name=create-schedule-form]').find('[name^="action[type]"]').toArray();

        var action_error = false;

        action_inputs.forEach(function (item,index) {

          var input = $(item),
              input_field = input.closest('.form-group');

          var num = input.attr('name').replace(/^\D+|\D+$/g, "");

          $('[name=create-schedule-form]').find('span[for="action['+num+']"]').remove();

          if (response['action[type]['+num+']'] != null || response['action[bot]['+num+']'] != null) {

            action_error = true;

            var errorHtml = ['<span class="help-block" for="action['+num+']">',
                      'This field is invalid',
                  '</span>'].join('');

            input_field
                .one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
                    $(this).removeClass("animated shake");
                });

            input.closest('.input-group').after(errorHtml);
          }
        });

        if (action_error == true) {
          $('[name=create-schedule-form]').find('[name^="action[type][0]"]').closest('.form-group').removeClass('has-success has-warning animated shake').addClass('has-error animated shake');
        } else {
          $('[name=create-schedule-form]').find('[name^="action[type][0]"]').closest('.form-group').removeClass('has-error has-warning animated shake').addClass('has-success');
        }

        // This is for frequency

        var frequency_inputs = $('[name=create-schedule-form]').find('[name^="frequency[value]"]').toArray();

        var frequency_error = false;

        frequency_inputs.forEach(function (item,index) {

          var input = $(item),
              input_field = input.closest('.form-group');

          var num = input.attr('name').replace(/^\D+|\D+$/g, "");

          $('[name=create-schedule-form]').find('span[for="frequency['+num+']"]').remove();

          if (response['frequency[value]['+num+']'] != null || response['frequency[unit]['+num+']'] != null || response['frequency[at]['+num+']'] != null) {

            frequency_error = true;

            var errorHtml = ['<span class="help-block" for="frequency['+num+']">',
                      'This field is invalid',
                  '</span>'].join('');

            input_field
                .one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
                    $(this).removeClass("animated shake");
                });

            input.closest('.input-group').after(errorHtml);
          }
        });

        if (frequency_error) {
          $('[name=create-schedule-form]').find('[name^="frequency[value][0]"]').closest('.form-group').removeClass('has-success has-warning animated shake').addClass('has-error animated shake');
        } else {
          $('[name=create-schedule-form]').find('[name^="frequency[value][0]"]').closest('.form-group').removeClass('has-error has-warning animated shake').addClass('has-success');
        }

        // This is for condition

        var condition_inputs = $('[name=create-schedule-form]').find('[name^="condition[bot]"]').toArray();

        var condition_error = false;

        var condition_type = $('[name=create-schedule-form]').find('[name^="condition[type]"]');

        if (response['condition[type'] != null || response['condition[method]']) {
          condition_error = true;

          var errorHtml = ['<span class="help-block" for="condition['+num+']">',
                      'This field is invalid',
                  '</span>'].join('');

          $('[name=create-schedule-form]').find('[name="condition[type]"]').closest('.form-group').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
                    $(this).removeClass("animated shake");
                });

          $('[name=create-schedule-form]').find('[name="condition[type]"]').closest('.input-group').after(errorHtml);
        }

        condition_inputs.forEach(function (item,index) {

          var input = $(item),
              input_field = input.closest('.form-group');

          var num = input.attr('name').replace(/^\D+|\D+$/g, "");

          $('[name=create-schedule-form]').find('span[for="condition['+num+']"]').remove();

          if (response['condition[bot]['+num+']'] != null || response['condition[state]['+num+']'] != null) {

            condition_error = true;

            var errorHtml = ['<span class="help-block" for="condition['+num+']">',
                      'This field is invalid',
                  '</span>'].join('');

            input_field
                .one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
                    $(this).removeClass("animated shake");
                });

            input.closest('.input-group').after(errorHtml);
          }
        });

        if (condition_error) {
          $('[name=create-schedule-form]').find('[name^="condition[bot][0]"]').closest('.form-group').removeClass('has-success has-warning animated shake').addClass('has-error animated shake');
        } else {
          $('[name=create-schedule-form]').find('[name^="condition[bot][0]"]').closest('.form-group').removeClass('has-error has-warning animated shake').addClass('has-success');
        }

        var inputs = $('[name=create-schedule-form]').find('input:visible:not(.tt-hint), select:visible, textarea:visible').not('[name^=action]').not('[name^=frequency]').not('[name^=condition]');

        inputs = inputs.toArray();

        if (response['success'] != null) {

            // window.location.href = '{{ route('h::s::index') }}';

        } else {

          $('#error-alert').slideUp('slow','linear', function() {
              this.remove()
          });

          var focus_to = false;

          inputs.forEach(function(item, index) {

              var input = $(item),
                  input_name = input.attr('name'),
                  input_error = response[input.attr('name')],
                  input_field = input.closest('.form-group'),
                  input_type = input.attr('type');

              $('[name=create-schedule-form]').find('span[for="' + input_name + '"]').remove();

              input_field.removeClass('has-error has-success has-warning animated shake');

              if (input_error == null) {

                  input_field.addClass('has-success');

              } else {

                  var errorHtml = ['<span class="help-block" for="' + input_name + '">',
                      input_error,
                  '</span>'].join('');

                  input_field
                      .addClass('has-error animated shake')
                      .one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
                          $(this).removeClass("animated shake");
                      });

                  input.closest('div:not(.input-group)').append(errorHtml);

                  if (focus_to == false) {
                      focus_to = true;
                      input.focusTo();
                  }
              }
          });

        }
      }
    });
    return false;
  }
  $('.datetimepicker').datetimepicker();
  <?php
    $data = explode('|',$sche['data']);
    $i=0;
    foreach ($data as $single_data) {
      echo 'changeFrequency('.$i.');';
      $i++;
    }
  ?>
</script>
@endsection
@section('body')
{!! content_header('Edit schedule', [
    'Hub' => route('h::edit'),
    'Schadule' => route('h::s::index'),
    'Edit' => 'active']) !!}
<div class="row">
    <div class="col-sm-12">
        <div class="card-box">
    {!! Form::open(['route' => ['h::s::edit',$sche['id']], 'class' => 'form-horizontal', 'name' => 'edit-schedule-form', 'onsubmit' => 'return editSchedule()']) !!}
      <div class="form-group">
        {!! Form::label('status', 'Status', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
          <?php
            switch ($sche['status']) {
              case 0:
                echo '<h4><span class="label label-danger" id="scheduleTus">Deactivated</span></h4>';
                break;
              case 1:
                echo '<h4><span class="label label-primary" id="scheduleTus">Activated</span></h4>';
                break;
            }
          ?>
        </div>
      </div>
      <div class="form-group">
        {!! Form::label('name', 'Name', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
          {!! Form::text('name', $sche['name'], ['class' => 'form-control']) !!}
        </div>
      </div>
      <div class="form-group">
        {!! Form::label('description', 'Description', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
          {!! Form::textarea('description', $sche['description'], ['class' => 'form-control']) !!}
        </div>
      </div>
      <div class="form-group">
        {!! Form::label('action', 'Action', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
          <?php
            $schedule_actions = explode('|',$sche->action);
          ?>
          <div id="actions" data-count="{{ count($schedule_actions) }}">
            <?php
              for($i=0; $i<count($schedule_actions); $i++) {

                $schedule_actions[$i] = explode(',',$schedule_actions[$i]);

            ?>
              <div class="input-group">
                <div class="input-group-btn">
                  {!! Form::select('action[type]['.$i.']', ['1' => 'Toggle', '2' => 'Turn on', '3' => 'Turn off'], $schedule_actions[$i][0], ['class' => 'form-control', 'style' => 'margin-top: -5px']) !!}
                </div>
                {!! Form::text('action[bot]['.$i.']', $schedule_actions[$i][1], ['class' => 'form-control b-left-0']) !!}
              </div>
            <?php
              }
            ?>
          </div>
          {!! Form::button('<span class="btn-label"><i class="fa fa-plus" aria-hidden="true"></i></span>Add action', ['class' => 'btn btn-default pull-right','onclick' => 'addAction()']) !!}
        </div>
      </div>
      <div class="form-group">
        {!! Form::label('type', 'Type', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
          {!! Form::select('type', ['1' => 'One time', '2' => 'Repeat'], $sche['type'], ['class' => 'form-control', 'onChange' => 'changeType()', 'style' => 'width: 100% !important']) !!}
        </div>
      </div>
      <div id="data">
        @if ($sche['type'] == 1)
        <div class="form-group">
          {!! Form::label('time', 'Time', ['class' => 'col-sm-2 control-label']) !!}
          <div class="col-sm-10">
            {!! Form::text('time', $sche['time'], ['class' => 'form-control datetimepicker']) !!}
          </div>
        </div>
        @else
        <div class="form-group">
          {!! Form::label('frequency', 'Frequency', ['class' => 'col-sm-2 control-label']) !!}
          <div class="col-sm-10">
            <?php
            $data = explode('|',$sche['data']);
            ?>
            <div id="frequencies" data-count="1" class="m-b-5">
            <?php
              $i=0;
              foreach ($data as $single_data) {
                $single_data = explode(',',$single_data);
            ?>
              <div class="input-group m-t-5">
                <span class="input-group-addon">Every</span>
                {!! Form::text('frequency[value]['.$i.']', $single_data[1], ['class' => 'form-control b-right-0']) !!}
                <div class="input-group-btn">
                  {!! Form::select('frequency[unit]['.$i.']', ['1' => 'minute(s)', '2' => 'hour(s)', '3' => 'day(s)', '4' => 'week(s)', '5' => 'month(s)', '6' => 'year(s)'], $single_data[0], ['class' => 'form-control', 'onChange' => 'changeFrequency('.$i.')']) !!}
                </div>
                @if (isset($single_data[2]) && $single_data[2] != null)
                  <span class="input-group-addon b-left-0 b-right-0" id="fre-at-text-0">At</span>
                  {!! Form::text('frequency[at]['.$i.']', $single_data[2], ['id' => 'fre-at-input-0', 'class' => 'form-control datetimepicker']) !!}
                @else
                  <span class="input-group-addon b-left-0 b-right-0" id="fre-at-text-0" style="display: none">At</span>
                  {!! Form::text('frequency[at]['.$i.']', null, ['id' => 'fre-at-input-0', 'class' => 'form-control datetimepicker', 'readonly' => 'readonly', 'style' => 'display: none']) !!}
                @endif
              </div>
            <?php
              $i++;
              }
            ?>
            </div>
          {!! Form::button('<span class="btn-label"><i class="fa fa-plus" aria-hidden="true"></i></span>Add frequency', ['class' => 'btn btn-default pull-right', 'onclick' => 'addFrequency()']) !!}
          </div>
        </div>'
        @endif
      </div>
      <div class="form-group">
        {!! Form::label('condition', 'Condition', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
          <div class="input-group">
            {!! Form::select('condition[type]', ['0' => 'Off', '1' => 'Work if', '2' => 'Not work if'], null, ['class' => 'form-control', 'onChange' => 'changeCondition()']) !!}
            <span id="condMethod"></span>
          </div>
          <div id='conditions'></div>
          <div id='add-condition-btn'></div>
        </div>
      </div>
      <div class="form-group">
        {!! Form::label('activate_after', 'Activate after', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
          {!! Form::text('activate_after', $sche['activate_after'], ['class' => 'form-control datetimepicker']) !!}
          <span class="help-block m-b-0">
            Leave blank to activate immediately
          </span>
        </div>
      </div>
      <div class="form-group">
        {!! Form::label('deactivate_after', 'Deactivate after', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
          <div class="input-group">
            {!! Form::text('deactivate_after_times', $sche['deactivate_after_times'], ['class' => 'form-control']) !!}
            <span class="input-group-addon b-left-0 b-right-0">
              time(s) or
            </span>
            {!! Form::text('deactivate_after_datetime', $sche['deactivate_after_datetime'], ['class' => 'form-control datetimepicker']) !!}
          </div>
          <span class="help-block m-b-0">
            Leave blank to avoid (infinitive loop or deactivate manually)
          </span>
        </div>
      </div>
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
