@extends('hub.master')
@section('title','Notification')
@section('additionHeader')
@endsection
@section('additionFooter')
@endsection
@section('body')
@header('Notification', [
    'Hub' => '#',
    'Notification' => 'active'
])
<div class="row">
    <div class="col-sm-12">
        <div class="card-box">
        <h3 class="m-t-0 m-b-15 header-title"><b>Notification list</b></h3>
        <div class="box-body">
            <div id="noti-list">
            @if(count($notis) > 0)
              @foreach ($notis as $noti)
                <a href="{{ $noti['href'] }}" class="list-group-item" id="noti{{ $noti['id'] }}" onclick="readNotify({{ $noti['id'] }})">
                  <div class="media">
                     <div class="pull-left p-r-10">
                        <?php
                          $holder_data = explode(':',$noti['holder'])
                        ?>
                        @if ($holder_data[0] == 'icon')
                          <em class="fa fa-{{ $holder_data[1] }} fa-2x text-custom"></em>
                        @endif
                        @if ($holder_data[0] == 'image')
                          <img src="@asset($holder_data[1])" class='img-noti'>
                        @endif
                     </div>
                     <div class="media-body">
                        <h5 class="media-heading">{{ $noti['subject'] }}</h5>
                        <p class="m-0">
                            <small>{{ $noti['body'] }}</small>
                        </p>
                     </div>
                  </div>
               </a>
              @endforeach
            @endif
            </div>
        </div>
        </div>
    </div>
</div>
@endsection
