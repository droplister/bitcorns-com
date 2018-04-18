@extends('layouts.app')

@section('title', 'Bitcorn Farms')

@section('content')
<div class="container">
    <div class="dropdown float-right mt-3 show">
        <a class="btn btn-primary dropdown-toggle" href="{{ url(route('players.index')) }}" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
           Sort
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
            <h6 class="dropdown-header">By Resource</h6>
            <a class="dropdown-item" href="{{ url(route('players.index', ['sort' => 'access'])) }}"><i class="fa fa-{{ $sort === 'access' ? 'check-' : '' }}circle-o mr-1"></i> Most Crops</a>
            <a class="dropdown-item" href="{{ url(route('players.index', ['sort' => 'reward'])) }}"><i class="fa fa-{{ $sort === 'reward' ? 'check-' : '' }}circle-o mr-1"></i> Most Bitcorn</a>
            <a class="dropdown-item" href="{{ url(route('players.index', ['sort' => 'rewards'])) }}"><i class="fa fa-{{ $sort === 'rewards' ? 'check-' : '' }}circle-o mr-1"></i> Most Harvests</a>
            <h6 class="dropdown-header">Chronological</h6>
            <a class="dropdown-item" href="{{ url(route('players.index', ['sort' => 'oldest'])) }}"><i class="fa fa-{{ $sort === 'oldest' ? 'check-' : '' }}circle-o mr-1"></i> Oldest Farms</a>
            <a class="dropdown-item" href="{{ url(route('players.index', ['sort' => 'newest'])) }}"><i class="fa fa-{{ $sort === 'newest' ? 'check-' : '' }}circle-o mr-1"></i> Newest Farms</a>
            <a class="dropdown-item" href="{{ url(route('players.index', ['sort' => 'updated'])) }}"><i class="fa fa-{{ $sort === 'updated' ? 'check-' : '' }}circle-o mr-1"></i> Updated Farms</a>
            <h6 class="dropdown-header">Szaboan Desert</h6>
            <a class="dropdown-item" href="{{ url(route('players.index', ['sort' => 'no-access'])) }}"><i class="fa fa-{{ $sort === 'no-access' ? 'check-' : '' }}circle-o mr-1"></i> No Croppers</a>
        </div>
    </div>
    <h1 class="display-4 mt-5 mb-4">
        <span class="d-none d-sm-inline">Bitcorn</span> Farms
        <small class="lead d-none d-sm-inline">{{ $players->total() }} Worldwide</small>
    </h1>
    <div class="row">
        @foreach($players as $player)
        <div class="col-12 col-sm-6 col-md-4 mt-4 mb-2">
            <div class="card">
                <a href="{{ url(route('players.show', ['show' => $player->address])) }}">
                    <img src="{{ $player->display_thumb_url }}" alt="{{ $player->name }}" class="card-img-top" />
                </a>
                <div class="card-body">
                    <a href="{{ url(route('players.show', ['show' => $player->address])) }}" class="btn btn-outline-primary pull-right">
                        <i class="fa fa-map-marker"></i> VISIT
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
                            Harvests: {{ $player->reward_count }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if($loop->iteration % 3 === 0 && ! $loop->last)
            <div class="w-100"></div>
        @endif
        @endforeach
    </div>
    <br />
    {!! $players->links('pagination::bootstrap-4') !!}
</div>
@endsection