<div id="sidebar-menu">
  <ul>
  @foreach ($menu as $title => $data)

    @if (!is_array($data))

      @if ($data == 'divider')
        <div class="divider"></div>
      @else
        <li class="text-muted menu-title">{{ $data }}</li>
      @endif

    @elseif (array_key_exists('sub',$data))

    <li class="has_sub">
      <a class="waves-effect @if (in_array(request()->url(),array_column($data['sub'],'href'))) active @endif">
        <i class="{{ $data['icon'] }}" aria-hidden="true"></i><span>{{ $title }}</span>
      </a>
      <ul class="list-unstyled">

        @foreach ($data['sub'] as $title2 => $data2)

        <li @if (request()->url() == $data2['href']) class="active" @endif>
          <a class="waves-effect" href="{{ $data2['href'] }}">
            <i class="{{ $data2['icon'] }}"></i> <span>{{ $title2 }}</span>
          </a>
        </li>

        @endforeach

      </ul>
    </li>

    @else

    <li>
      <a href="{{ $data['href'] }}" class="waves-effect @if (request()->url() == $data['href']) active @endif">
        <i class="{{ $data['icon'] }}" aria-hidden="true"></i><span>{{ $title }}</span>
      </a>
    </li>

    @endif


  @endforeach
  </ul>
  <div class="clearfix"></div>
</div>
