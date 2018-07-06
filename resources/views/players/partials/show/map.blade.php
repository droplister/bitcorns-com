@if($player->latitude && $player->longitude)
<div class="row mt-5">
    <div class="col">
        <div class="card">
            <div class="card-header">
                Location <small class="text-muted">{{ $player->latitude }}, {{ $player->longitude }}</small>
            </div>
            <google-map v-bind:lat="{{ $player->latitude }}" v-bind:lng="{{ $player->longitude }}" v-bind:zoom="8"></google-map>
        </div>
    </div>
</div>
@endif