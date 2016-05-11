<ul class="sidebar-menu">
  @foreach ($menu as $key => $value)
    @if (array_key_exists('nextLevel',$value))
    <li class="treeview @if (in_array(request()->url(),array_column($value['nextLevel'],'link'))) active @endif">
      <a href="#">
        <i class="fa {{ $value['icon'] }}"></i> <span>{{ $key }}</span>
        <i class="fa fa-angle-left pull-right"></i>
      </a>
      <ul class="treeview-menu">
        @foreach ($value['nextLevel'] as $key2 => $value2)
        <li @if (request()->url() == $value2['link']) class="active" @endif>
          <a href="{{ $value2['link'] }}">
            <i class="fa {{ $value2['icon'] }}"></i> <span>{{ $key2 }}</span>
          </a>
        </li>
        @endforeach
      </ul>
    </li>
    @else
    <li @if (request()->url() == $value['link']) class="active" @endif>
      <a href="{{ $value['link'] }}">
        <i class="fa {{ $value['icon'] }}"></i> <span>{{ $key }}</span>
      </a>
    </li>
    @endif
  @endforeach
</ul>
