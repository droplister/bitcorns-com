<div style="position: relative;">
    <a href="{{ url(route('players.edit', ['player' => $player->address])) }}" class="btn btn-sm btn-light" style="position: absolute; top: 15px; right: 15px;">
        <i class="fa fa-edit"></i> Edit
    </a>
    <img src="{{ $player->display_image_url }}" width="100%" height="auto" />
</div>