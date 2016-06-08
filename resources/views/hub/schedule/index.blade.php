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
        <table class="table table-hover m-t-10">
          <tbody>
          <tr>
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
          <td><a href="{{ route('h::s::edit',$schedule['id']) }}">{!! Form::button('<span class="btn-label"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span>Edit', ['type' => 'submit', 'class' => 'btn btn-xs btn-primary']) !!}</a></td>
        </tr>
        @endforeach
        </tbody>
        </table>
        @else
        <p>No schedule found.</p>
        @endif
    </div>
  </div>
</div>
@endsection
