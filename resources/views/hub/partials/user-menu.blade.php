<li class="dropdown pull-left">
    <a href="" class="dropdown-toggle profile" data-toggle="dropdown" aria-expanded="true">
      <img src="@asset($user->avatar)" alt="user-img" class="img-circle">
      <span class="hidden-sm hidden-xs user-name">{{ $user->username }}</span>
    </a>
    <ul class="dropdown-menu">
        @if (!session()->has('currentHub'))
            <li><a href="@route('h::index')"><i class="ti-target m-r-10"></i>Hub login</a></li>
        @endif
        <li><a href="@route('a::edit')"><i class="fa fa-user m-r-10"></i>Profile</a></li>
        <li><a href="javascript:void(0)"><i class="fa fa-cog m-r-10"></i>Settings</a></li>
        <li><a href="javascript:void(0)"><i class="fa fa-lock m-r-10"></i>Lock screen</a></li>
        <li><a class="custom" href="javascript:logout()"><i class="fa fa-power-off m-r-5"></i> Logout</a></li>
    </ul>
</li>
