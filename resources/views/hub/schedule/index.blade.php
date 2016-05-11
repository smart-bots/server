@extends('hub.master')
@section('title','Hub Schedules')
@section('additionHeader')
@endsection
@section('additionFooter')
@endsection
@section('body')
{{ dd($schedules) }}
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Hub Edit
    <small>Setting your hub</small>
  </h1>
  {{breadcrumb(['Hub' => route('h::edit'), 'Schedule' => route('h::b::index'), 'Index' => 'active'])}}
</section>
<!-- Main content -->
<section class="content">
  <!-- Default box -->
  <div class="box">
    <div class="box-header">
      <h3 class="box-title">Member list</h3>
      <a href='{{ route('h::m::create') }}'>{!! Form::button('<i class="fa fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;Add member', ['type' => 'submit', 'class' => 'btn btn-success pull-right']) !!}</a>
    </div>
    <div class="box-body">
      <ul class="users-list clearfix users-list-mini">
        @if (count($members)>0)
        @foreach ($members as $member)
        <li>
          <img class="img-thumbnail" src="{{ asset($member['user']['avatar']) }}">
          <a class="users-list-name" href="{{ route('h::m::edit',$member['id'])}}">{{ $member['user']['name'] }}</a>
          <span class="users-list-date">{{ $member['bots'] }} bots</span>
        </li>
        @endforeach
        @else
        <p>No member found.</p>
        @endif
      </ul>
    </div>
  </div>
</section>
<!-- /.content -->
@endsection
