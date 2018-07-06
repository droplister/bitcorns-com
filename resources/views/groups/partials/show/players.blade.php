<div class="row mt-4">
    @foreach($players as $player)
    <div class="col-12 col-sm-6 col-md-4 mt-4 mb-2">
        @include('players.partials.index.player')
    </div>
    @if($loop->iteration % 3 === 0 && ! $loop->last)
        <div class="w-100"></div>
    @endif
    @endforeach
</div>