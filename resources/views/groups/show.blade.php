@extends('layouts.app')

@section('title', $group->name)

@section('content')
<google-map v-bind:lat="39.828175" v-bind:lng="-98.5795" v-bind:zoom="2" group="{{ $group->slug }}"></google-map>
<div class="container">
    <h1 class="display-4 mt-5 mb-4">{{ $group->name }}</h1>
    <p>{{ $group->description }}</p>
    @include('partials.session')
    @if(Auth::guard('player')->guest() || Auth::guard('player')->check() && Auth::guard('player')->user()->group_id !== $group->id)
    <div class="row mt-5">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    Become a Member <a href="https://youtu.be/oLBSZdzm_A0" class="float-right" target="_blank"><i class="fa fa-external-link"></i> Tutorial</a>
                </div>
                <div class="card-body">
                    @if('open' === $group->type)
                    <form role="form" method="POST" action="{{ url(route('memberships.store', ['group' => $group->slug])) }}">
                        {{ csrf_field() }}
                        @if(Auth::guard('player')->check())
                        <input id="address" type="hidden" name="address" value="{{ Auth::guard('player')->user()->address }}">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> Join Co-Op
                            </button>
                        </div>
                        @else
                        <div class="form-group">
                            <label for="address">Member</label>
                            <input id="address" type="text" class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}" name="address" value="{{ old('address') }}" placeholder="19QWXpMXeLkoEKEJv2xo9rn8wkPCyxACSX">
                            @if ($errors->has('address'))
                                <div class="invalid-feedback">
                                     <strong>{{ $errors->first('address') }}</strong>
                                </div>
                            @else
                                <small id="memberHelp" class="form-text text-muted">Enter your address. You can only have one membership.</small>
                            @endif
                        </div>
                        <hr class="mb-4" />
                        <div class="form-group">
                            <label for="timestamp">Timestamp</label>
                            <input id="timestamp" type="text" class="form-control{{ $errors->has('timestamp') ? ' is-invalid' : '' }}" name="timestamp" value="{{ old('timestamp') ? old('timestamp') : \Carbon\Carbon::now() }}" required>
                            @if ($errors->has('timestamp'))
                                <div class="invalid-feedback">
                                     <strong>{{ $errors->first('timestamp') }}</strong>
                                </div>
                            @else
                                <small id="timestampHelp" class="form-text text-muted">Sign this timestamp to authorize update.</small>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="signature">Signature</label>
                            <input id="signature" type="text" class="form-control{{ $errors->has('signature') ? ' is-invalid' : '' }}" name="signature" value="{{ old('signature') }}" required>
                            @if ($errors->has('signature'))
                                <div class="invalid-feedback">
                                     <strong>{{ $errors->first('signature') }}</strong>
                                </div>
                            @else
                                <small id="signatureHelp" class="form-text text-muted">Enter your signed timestamp message. <a href="https://youtu.be/AvPdaNb35qY" target="_blank"><i class="fa fa-external-link"></i> Tutorial</a></small>
                            @endif
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> Save
                            </button>
                        </div>
                        @endif
                    </form>
                    @else
                        <p class="card-text"><em>This is a closed group and requires an invitation to join.</em></p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
    <div class="row mt-4">
        @foreach($players as $player)
        <div class="col-12 col-sm-6 col-md-4 mt-4 mb-2">
            <div class="card">
                <a href="{{ url(route('players.show', ['show' => $player->address])) }}">
                    <img src="{{ $player->display_image_url }}" alt="{{ $player->name }}" class="card-img-top" />
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
                            Harvests: {{ $player->rewards_count }}
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
</div>
@endsection