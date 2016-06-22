<?php
    use SmartBots\Bot;
?>
@extends('hub.master')
@section('title','Hub events')
@section('additionHeader')
@endsection
@section('additionFooter')
    <script>
        function eventFire(id) {
            $.ajax({
                url : '@route('h::e::fire')',
                type : 'post',
                data : {
                  _token: '{{ csrf_token() }}',
                  id: id
                },
                dataType : 'json',
                success : function (response)
                {
                    swal({
                        title: 'Successfully',
                        text: 'Event fired',
                        type: "success",
                        confirmButtonText: 'Good',
                    });
                }
            });
            return false;
        }
    </script>
@endsection
@section('body')
{!! content_header('Hub events', [
    'Hub' => route('h::edit'),
    'Event' => route('h::e::index'),
    'List' => 'active']) !!}
<div class="row">
    <div class="col-sm-12">
        <div class="card-box">
      <a href='{{ route('h::e::create') }}'>{!! Form::button('<span class="btn-label"><i class="fa fa-plus" aria-hidden="true"></i></span>Create event', ['type' => 'submit', 'class' => 'btn btn-default waves-effect waves-light btn-create']) !!}</a>
        @if (count($events)>0)
        <div class="table-responsive m-t-10">
        <table class="table table-hover table-striped">
          <thead>
          <tr>
            <th>Name</th>
            <th>Trigger</th>
            <th>Status</th>
            <th></th>
          </tr>
          </thead>
          <tbody>
        @foreach ($events as $event)
        <tr>
          <td>{{ $event['name'] }}</td>
          <td>@if (!empty($event['trigger_bot'])) {{ Bot::findOrFail($event['trigger_bot'])->name }} @endif</td>
          <td><?php
            switch ($event['status']) {
              case 0:
                echo '<span class="label label-danger" id="eventTus">Deactivated</span>';
                break;
              case 1:
                echo '<span class="label label-primary" id="eventTus">Activated</span>';
                break;
            }
          ?></td>
          <td>
            {!! Form::button('<span class="btn-label"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span>Fire', ['type' => 'button', 'class' => 'btn btn-xs btn-default m-r-5', 'onclick' => 'eventFire('.$event['id'].')']) !!}
            <a href="{{ route('h::e::edit',$event['id']) }}">
                {!! Form::button('<span class="btn-label"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span>View', ['type' => 'button', 'class' => 'btn btn-xs btn-primary']) !!}
            </a>
          </td>
        </tr>
        @endforeach
        </tbody>
        </table>
        </div>
        @else
        <p>No event found.</p>
        @endif
    </div>
  </div>
</div>
@endsection
