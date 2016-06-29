<?php
    use SmartBots\{Hub, Bot, Event};
?>
@extends('hub.master')
@section('title','Hub automations')
@section('additionHeader')
@endsection
@section('additionFooter')
@endsection
@section('body')
{!! content_header('Hub automations', [
    'Hub' => route('h::edit'),
    'Automation' => route('h::a::index'),
    'List' => 'active']) !!}
<div class="row">
    <div class="col-sm-12">
        <div class="card-box">
        @if(auth()->user()->can('createAutomations',Hub::findOrFail(session('currentHub'))))
        <a href='{{ route('h::a::create') }}'>{!! Form::button('<span class="btn-label"><i class="fa fa-plus" aria-hidden="true"></i></span>Create automation', ['type' => 'submit', 'class' => 'btn btn-default waves-effect waves-light btn-create']) !!}</a>
        @endif
        @if (count($automations)>0)
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
        @foreach ($automations as $automation)
        <tr>
          <td>{{ $automation['name'] }}</td>
          <td>@if($automation['trigger_type'] != 4) {{ Bot::findOrFail($automation['trigger_id'])->name }} @else {{ Event::findOrFail($automation['trigger_id'])->name }} @endif</td>
          <td><?php
            switch ($automation['status']) {
              case 0:
                echo '<span class="label label-danger" id="automationTus">Deactivated</span>';
                break;
              case 1:
                echo '<span class="label label-primary" id="automationTus">Activated</span>';
                break;
            }
          ?></td>
          <td>
            <a href="{{ route('h::a::edit',$automation['id']) }}">
                {!! Form::button('<span class="btn-label"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span>View', ['type' => 'button', 'class' => 'btn btn-xs btn-primary']) !!}
            </a>
          </td>
        </tr>
        @endforeach
        </tbody>
        </table>
        </div>
        @else
        <p>No automation found.</p>
        @endif
    </div>
  </div>
</div>
@endsection
