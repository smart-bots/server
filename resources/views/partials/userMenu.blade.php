<li class="dropdown user user-menu">
  <a href="#" class="dropdown-toggle" data-toggle="dropdown">
    <img src="{{ asset($user->avatar) }}" class="user-image">
    <span class="hidden-xs">{{ $user->name }}</span>
  </a>
  <ul class="dropdown-menu">
    <li class="user-header">
      <img src="{{ asset($user->avatar) }}" class="img-circle">
      <p>
        {{ $user->username }} - {{ $user->name }}
        <small>This is nothing</small>
      </p>
    </li>
    <li class="user-footer">
      <div class="pull-left">
        <a href="{{ route('a::edit') }}" class="btn btn-default btn-flat"><i class="fa fa-user" aria-hidden="true"></i>&nbsp;&nbsp;Profile</a>
      </div>
      <div class="pull-right">
        <a href="javascript:logout()" class="btn btn-default btn-flat"><i class="fa fa-sign-out" aria-hidden="true"></i>&nbsp;&nbsp;Logout</a>
      </div>
    </li>
  </ul>
</li>
