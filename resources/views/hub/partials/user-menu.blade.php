<li class="dropdown pull-left">
    <a href="" class="dropdown-toggle profile" data-toggle="dropdown" aria-expanded="true">
      <img src="@asset($user->avatar)" alt="user-img" class="img-circle">
      <span class="hidden-sm hidden-xs user-name">{{ $user->username }}</span>
    </a>
    <ul class="dropdown-menu">
        @if (!session()->has('currentHub'))
            <li><a href="@route('h::index')"><i class="ti-target m-r-10"></i>@trans('hub/hub.user_hub_login')</a></li>
        @endif
        <li><a href="@route('a::edit')"><i class="fa fa-user m-r-10"></i>@trans('hub/hub.user_profile')</a></li>
        <li><a href="javascript:void(0)"><i class="fa fa-cog m-r-10"></i>@trans('hub/hub.user_settings')</a></li>
        <li><a class="custom" href="javascript:logout()"><i class="fa fa-power-off m-r-5"></i>&nbsp;@trans('hub/hub.user_logout')</a></li>
    </ul>
</li>
