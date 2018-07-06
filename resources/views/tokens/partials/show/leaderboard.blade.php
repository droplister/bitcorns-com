@if($players->total())
<div class="card mb-4">
    <div class="card-header">
        Leaderboard
    </div>
    <div class="table-responsive">
        <table class="table mb-0" style="overflow-y: auto;white-space: nowrap;">
            <tbody>
                @foreach($players as $player)
                <tr>
                    <th scope="row" class="pl-4">{{ $loop->iteration }}.</th>
                    <td><a href="{{ $player->url }}">{{ $player->name }}</a></td>
                    <td class="text-muted d-none d-sm-block"><small>{{ $player->address }}</small></td>
                    <td class="text-right">{{ $token->divisible ? fromSatoshi($player->pivot->quantity) : number_format($player->pivot->quantity) }} <span class="d-none d-sm-inline">{{ $token->name }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif