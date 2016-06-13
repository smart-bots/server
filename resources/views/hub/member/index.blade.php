@extends('hub.master')
@section('title','Hub Members')
@section('additionHeader')
@endsection
@section('additionFooter')
@endsection
@section('body')
  @header('Hub members', [
    'Hub' => route('h::edit'),
    'Member' => '#'])
<div class="row">
    <div class="col-sm-12">
        <div class="card-box">
      <a href='@route('h::m::create')'>{!! Form::button('<span class="btn-label"><i class="fa fa-plus" aria-hidden="true"></i></span>Add member', ['type' => 'submit', 'class' => 'btn btn-default waves-effect waves-light btn-create']) !!}</a>
      @if (count($members)>0)
        <ul class="hubs-list clearfix">
        @foreach ($members as $member)
          <li>
            <a href="@route('h::m::edit',$member['id'])">
              <img class="img-thumbnail" src="@asset($member['user']['avatar'])">
              <span class="hubs-list-name">{{ $member['user']['name'] }}</span>
              <span class="hubs-list-date text-muted">{{ $member['bots'] }} bots</span>
            </a>
          </li>
        @endforeach
         </ul>
      @else
        <p>No member found.</p>
      @endif
    </div>
  </div>
</div>
@endsection
