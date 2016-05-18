@extends('hub.master')
@section('title','Hub Schedules')
@section('additionHeader')
@endsection
@section('additionFooter')
@endsection
@section('body')
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Schedules list
    <small>All schedules</small>
  </h1>
  {{breadcrumb(['Hub' => route('h::edit'), 'Schedule' => route('h::b::index'), 'Index' => 'active'])}}
</section>
<!-- Main content -->
<section class="content">
  <!-- Default box -->
  <div class="box">
    <div class="box-header">
      <h3 class="box-title">Schedules list</h3>
      <a href='{{ route('h::s::create') }}'>{!! Form::button('<i class="fa fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;Create schedule', ['type' => 'submit', 'class' => 'btn btn-success pull-right']) !!}</a>
    </div>
    <div class="box-body">
      <ul class="users-list clearfix users-list-mini">
        @if (count($schedules)>0)
        <table class="table table-hover">
          <tbody><tr>
            <th>Name</th>
            <th>Description</th>
            <th>Status</th>
            <th></th>
          </tr>
        @foreach ($schedules as $schedule)
        <tr>
          <td>{{ $schedule['name'] }}</td>
          <td>{{ $schedule['description'] }}</td>
          <td><span class="label label-success">Approved</span></td>
          <td><a href="{{ route('h::s::edit',$schedule['id']) }}">{!! Form::button('<i class="fa fa-pencil-square-o" aria-hidden="true"></i>&nbsp;&nbsp;Edit', ['type' => 'submit', 'class' => 'btn btn-xs btn-primary']) !!}</a></td>
        </tr>
        @endforeach
        </tbody></table>
        @else
        <p>No schedule found.</p>
        @endif
      </ul>
    </div>
  </div>
</section>
<!-- /.content -->
@endsection
