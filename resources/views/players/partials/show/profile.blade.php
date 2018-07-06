<div class="row">
    <div class="col">
        <div class="card text-center">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" id="playerTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="true">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="history-tab" data-toggle="tab" href="#history" role="tab" aria-controls="history" aria-selected="true">History</a>
                    </li>
                    <li class="nav-item d-none d-sm-inline">
                        <a class="nav-link" id="battle-tab" data-toggle="tab" href="#battle" role="tab" aria-controls="battle" aria-selected="true">Battle</a>
                    </li>
                    @if($player->group_id)
                    <li class="nav-item">
                        <a class="nav-link" id="coop-tab" data-toggle="tab" href="#coop" role="tab" aria-controls="coop" aria-selected="true">Co-Op</a>
                    </li>
                    @endif
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="playerTabContent">
                    <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <h5 class="card-title">{{ $player->display_name }}</h5>
                        <p class="card-text">{{ $player->display_description }}</p>
                        <a href="https://xcpfox.com/address/{{ $player->address }}" class="btn btn-primary" target="_blank"><i class="fa fa-search"></i> View Address</a>
                    </div>
                    <div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="history-tab">
                        <h5 class="card-title">Farm Deed #{{ $player->tx->tx_index }}</h5>
                        <p class="card-text">{{ $player->name }} was established {{ $player->tx->display_confirmed_at }} by a CROPS {{ $player->type }}.</p>
                        <a href="https://xcpfox.com/tx/{{ $player->tx->tx_index }}" class="btn btn-primary" target="_blank"><i class="fa fa-search"></i> View Transaction</a>
                    </div>
                    <div class="tab-pane fade" id="battle" role="tabpanel" aria-labelledby="battle-tab">
                        <h5 class="card-title">Bitcorn Battle</h5>
                        <p class="card-text">Battle other bitcorn farms! (Requires <a href="{{ url(route('cards.show', ['card' => 'BATTLECORN'])) }}">1 BATTLECORN</a>.)</p>
                        <a href="https://bitcornbattle.com/?ref={{ $player->address }}" class="btn btn-primary" target="_blank"><i class="fa fa-search"></i> Learn More</a>
                    </div>
                    @if($player->group_id)
                    <div class="tab-pane fade" id="coop" role="tabpanel" aria-labelledby="coop-tab">
                        <h5 class="card-title">{{ $player->group->name }}</h5>
                        <p class="card-text">{{ $player->group->description }}</p>
                        <a href="{{ url(route('groups.show', ['group' => $player->group->slug])) }}" class="btn btn-primary"><i class="fa fa-edit"></i> Join Cooperative</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>