@extends('layouts.app')

@section('title', $player->display_name)

@section('content')

<div style="position: relative;">
    <a href="{{ url(route('players.edit', ['player' => $player->address])) }}" class="btn btn-sm btn-light" style="position: absolute; top: 15px; right: 15px;">
        <i class="fa fa-edit"></i> Edit
    </a>
    <img src="{{ $player->display_image_url }}" width="100%" height="auto" />
</div>

<div class="content">
    <div class="container">
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
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="playerTabContent">
                            <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <h5 class="card-title text-uppercase">{{ $player->display_name }}</h5>
                                <p class="card-text">{{ $player->description }}</p>
                                <a href="https://xchain.io/address/{{ $player->address }}" class="btn btn-primary" target="_blank"><i class="fa fa-search"></i> View Address</a>
                            </div>
                            <div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="history-tab">
                                <h5 class="card-title">Farm Deed #{{ $player->tx->tx_index }}</h5>
                                <p class="card-text">{{ $player->name }} was established {{ $player->tx->confirmed_at->format('M d, Y') }} by a CROPS {{ $player->type }}.</p>
                                <a href="https://xchain.io/tx/{{ $player->tx->tx_index }}" class="btn btn-primary" target="_blank"><i class="fa fa-search"></i> View Transaction</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col">
                <div class="card text-center">
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs" id="activityTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="activity-tab" data-toggle="tab" href="#activity" role="tab" aria-controls="activity" aria-selected="true">Activity</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="activityTabContent">
                            <div class="tab-pane fade show active" id="activity" role="tabpanel" aria-labelledby="activity-tab">
                                <div class="row mt-1 mb-2 text-left">
                                @foreach($player->balances as $balance)
                                    <div class="col-xs-12 col-sm-6 col-md-4 mb-2">
                                        <img src="{{ $balance->token->thumb_url }}" class="float-left mr-3" />
                                        <h4 class="card-title">{{ $balance->token->name }}</h4>
                                        <p class="card-text">{{ $balance->display_quantity }}</p>
                                    </div>
                                @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0 text-left">
                            <thead>
                                <tr>
                                    <th>Action</th>
                                    <th>Token</th>
                                    <th>Record</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($player->txs()->orderBy('tx_index', 'desc')->take(5)->get() as $tx)
                                <tr>
                                    <th>{{ $tx->type }}</th>
                                    <td><a href="{{ $tx->token->url }}">{{ $tx->token->name }}</a></td>
                                    <td class="text-muted">{{ $tx->tx_hash }}</td>
                                    <td>{{ $tx->confirmed_at->diffForHumans() }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @if($player->latitude && $player->longitude)
        <div class="row mt-4">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        Location <small class="text-muted">{{ $player->latitude }}, {{ $player->longitude }}</small>
                    </div>
                    <google-map established="{{ $player->tx->confirmed_at->format('M d, Y') }}" location="{{ $player->name }}" v-bind:latitude="{{ $player->latitude }}" v-bind:longitude="{{ $player->longitude }}" v-bind:radius="{{ $player->map_radius }}"></google-map>
                </div>
            </div>
        </div>
        @endif
    </div>
@endsection