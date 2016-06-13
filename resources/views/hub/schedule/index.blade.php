@extends('hub.master')
@section('title','Hub Schedules')
@section('additionHeader')
@endsection
@section('additionFooter')
@endsection
@section('body')
{!! content_header('Add new schedule', [
    'Hub' => route('h::edit'),
    'Schedule' => route('h::s::index'),
    'List' => 'active']) !!}
<div class="row">
    <div class="col-sm-12">
        <div class="card-box">
      <a href='{{ route('h::s::create') }}'>{!! Form::button('<span class="btn-label"><i class="fa fa-plus" aria-hidden="true"></i></span>Create schedule', ['type' => 'submit', 'class' => 'btn btn-default waves-effect waves-light btn-create']) !!}</a>
        @if (count($schedules)>0)
        <div class="table-responsive m-t-10">
        <table class="table table-hover table-striped">
          <thead>
          <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Status</th>
            <th></th>
          </tr>
          </thead>
          <tbody>
        @foreach ($schedules as $schedule)
        <tr>
          <td>{{ $schedule['name'] }}</td>
          <td>{{ $schedule['description'] }}</td>
          <td><?php
            switch ($schedule['status']) {
              case 0:
                echo '<span class="label label-danger" id="scheduleTus">Deactivated</span>';
                break;
              case 1:
                echo '<span class="label label-primary" id="scheduleTus">Activated</span>';
                break;
            }
          ?></td>
          <td><a href="{{ route('h::s::edit',$schedule['id']) }}">{!! Form::button('<span class="btn-label"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span>View', ['type' => 'submit', 'class' => 'btn btn-xs btn-primary']) !!}</a></td>
        </tr>
        @endforeach
        </tbody>
        </table>
        </div>
        @else
        <p>No schedule found.</p>
        @endif
    </div>
  </div>
</div>
@endsection
