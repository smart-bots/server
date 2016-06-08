@extends('hub.master')
@section('title','Test')
@section('additionHeader')

@endsection
@section('additionFooter')

@endsection
@section('body')
{!! content_header('Test template', [
    'Template' => '#',
    'Test' => 'active'
]) !!}
<div class="row">
    <div class="col-sm-12">
        <div class="card-box">
            <h4 class="m-t-0 header-title"><b>Test card</b></h4>
            <p class="text-muted font-13 m-b-0">Test content</p>
        </div>
    </div>
</div>
@endsection
