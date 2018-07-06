<ul class="nav nav-tabs card-header-tabs" role="tablist">
    <li class="nav-item">
        <a class="nav-link{{ $tab === 'profile' ? ' active' : '' }}" href="{{ url(route('players.edit', ['player' => $player->address])) }}" role="tab"><i class="fa fa-edit"></i> Profile</a>
    </li>
    <li class="nav-item">
        <a class="nav-link{{ $tab === 'image' ? ' active' : '' }}" href="{{ url(route('uploads.store', ['player' => $player->address])) }}" role="tab"><i class="fa fa-photo"></i> Image</a>
    </li>
    <li class="nav-item">
        <a class="nav-link{{ $tab === 'coop' ? ' active' : '' }}" href="{{ url(route('memberships.edit', ['player' => $player->address])) }}" role="tab"><i class="fa fa-sitemap"></i> Co-Op</a>
    </li>
    @if(Auth::guard('player')->check() && Auth::guard('player')->user()->address === $player->address)
    <li class="nav-item float-right">
        <a class="nav-link" href="{{ url('/lock') }}"
            onclick="event.preventDefault();
            document.getElementById('lock-form').submit();">
            <i class="fa fa-lock"></i> Lock
        </a>
        <form id="lock-form" action="{{ url(route('lock')) }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>
    </li>
    @elseif(Auth::guard('player')->guest())
    <li class="nav-item float-right{{ $tab === 'unlock' ? ' active' : '' }}">
        <a class="nav-link" href="{{ url(route('unlock', ['player' => $player->address])) }}" role="tab"><i class="fa fa-unlock"></i> Unlock</a>
    </li>
    @endif
</ul>