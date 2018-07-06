<section class="jumbotron text-center" style="background: url({{ $player->display_image_url }}) no-repeat center center / cover;">
    <a href="{{ url(route('players.show', ['player' => $player->address])) }}" class="btn btn-sm btn-light">
        <i class="fa fa-eye"></i> View
    </a>
</section>