<li class="dropdown pull-left noti">
  <a href="#" data-target="#" class="dropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="true">
      <i class="ti-bell" aria-hidden="true"></i> <span class="badge badge-xs badge-danger" id="noti-counter">{{ count($notis) }}</span>
  </a>
  <ul class="dropdown-menu dropdown-menu-lg">
      <li class="notifi-title"><span class="label label-default pull-right" id="noti-counter">{{ count($notis) }}</span>@trans('hub/hub.notification')</li>
      <li class="list-group nicescroll notification-list" id="noti-list">
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
      </li>
      <li>
          <a href="@route('h::n::index')" class="list-group-item text-right">
              <small class="font-600">See all notifications</small>
          </a>
      </li>
  </ul>
</li>
