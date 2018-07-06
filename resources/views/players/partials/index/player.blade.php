<div class="card">
    <a href="{{ url(route('players.show', ['show' => $player->address])) }}">
        <img src="{{ $player->display_thumb_url }}" alt="{{ $player->name }}" class="card-img-top" />
    </a>
    <div class="card-body">
        <a href="{{ url(route('players.show', ['show' => $player->address])) }}" class="btn btn-outline-primary pull-right">
            <i class="fa fa-map-marker"></i>
        </a>
        <h4 class="card-title">
            <a href="{{ url(route('players.show', ['show' => $player->address])) }}">
                {{ $player->display_name }}
            </a>
        </h4>
        <p class="card-text">
            {{ env('ACCESS_TOKEN_NAME') }}: {{ $player->accessBalance()->display_quantity }}
        </p>
    </div>
    <div class="card-footer">
        <div class="row text-muted">
            <div class="col">
                {{ $player->tx->display_confirmed_at }}
            </div>
            <div class="col text-right">
                Harvests: {{ $player->rewards_count }}
            </div>
        </div>
    </div>
</div>