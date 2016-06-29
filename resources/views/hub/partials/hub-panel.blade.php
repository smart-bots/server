<div class="user-details">
    <div class="pull-left">
        <img src="@asset($hub_panel->image)" alt="" class="thumb-md img-circle">
    </div>
    <div class="user-info">
        <div class="dropdown">
            <a href="#" class="dropdown-toggle user-name" data-toggle="dropdown" aria-expanded="false">{{ $hub_panel->name }}&nbsp;<span class="caret"></span></a>
            <!-- <ul class="dropdown-menu">
                <li><a href="javascript:void(0)"><i class="fa fa-user m-r-5"></i> Profile</a></li>
                <li><a href="javascript:void(0)"><i class="fa fa-cog m-r-5"></i> Settings</a></li>
                <li><a href="javascript:void(0)"><i class="fa fa-lock m-r-5"></i> Lock screen</a></li>
                <li class="divider"></li>
                <li><a href="javascript:void(0)"><i class="fa fa-sign-out m-r-5"></i> Logout</a></li>
            </ul> -->
        </div>
        <div class='title'>
            @if ($hub_panel->status == 1)
				<p onclick="hubDeactivate()" id="hubStatus" class="text-muted m-0" style="cursor: pointer"><i class="fa fa-circle text-custom"></i>&nbsp;<span>@trans('hub/hub.activated')</span></a>
			@else
				<p onclick="hubReactivate()" id="hubStatus" class="text-muted m-0" style="cursor: pointer"><i class="fa fa-circle text-danger"></i>&nbsp;<span>@trans('hub/hub.deactivated')</span></a>
			@endif
        </div>
    </div>
    <a href="javascript:hubLogout()" class="button pull-right">
      <i class="ti-power-off logout-left-side text-custom" aria-hidden="true" ></i>
    </a>
</div>
