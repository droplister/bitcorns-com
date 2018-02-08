@extends('layouts.app')

@section('title', 'Start a Coop')

@section('content')
<div class="content">
    <div class="container">
        @include('partials.session')
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        Start a Cooperative
                    </div>
                    <div class="card-body">
                        <p class="card-text">Given that they have <a href="{{ url(route('tokens.show', ['token' => 'CROPS'])) }}">0.1 CROPS</a>, farms can start cooperatives and cummulatively rank.</p>
                        <hr class="mt-4 mb-4" />
                        <form role="form" method="POST" action="{{ url(route('groups.store')) }}">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="address">Admin</label>
                                <input id="address" type="text" class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}" name="address" value="{{ old('address') }}" placeholder="19QWXpMXeLkoEKEJv2xo9rn8wkPCyxACSX">
                                @if ($errors->has('address'))
                                    <div class="invalid-feedback">
                                         <strong>{{ $errors->first('address') }}</strong>
                                    </div>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" placeholder="Bitcorn Coop">
                                @if ($errors->has('name'))
                                    <div class="invalid-feedback">
                                         <strong>{{ $errors->first('name') }}</strong>
                                    </div>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <input id="description" type="text" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" name="description" value="{{ old('description') }}" placeholder="Bitcorn Cooperative (Alpha AF)">
                                @if ($errors->has('description'))
                                    <div class="invalid-feedback">
                                         <strong>{{ $errors->first('description') }}</strong>
                                    </div>
                                @else
                                    <small id="descriptionHelp" class="form-text text-muted">Choose a name and slogan for your coop.</small>
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
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection