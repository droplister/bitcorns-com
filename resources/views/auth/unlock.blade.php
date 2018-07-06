@extends('layouts.app')

@section('title', 'Unlock')

@section('content')
<div class="container mt-5">
    <h1 class="display-4 mt-5 mb-5">
        Unlock
    </h1>
    <div class="row">
        <div class="col">
            <div class="card panel-default">
                <div class="card-header">
                    <i class="fa fa-unlock"></i> Unlock a Farm
                </div>

                <div class="card-body">
                    <p class="card-text">Use this form to "check out" time. This form will unlock your farm so that any changes made from your device are authorized for a specified period of time, rather than signing a message for every single change that you make. <em class="ml-3">"Work smarter, not harder." - Allan H. Mogensen</em>.</p>
                    <hr class="mt-4 mb-4" />
                    <form class="form" method="POST" action="{{ route('unlock') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                            <label for="address" class="control-label">Farm Address</label>
                            <input id="address" type="text" class="form-control" name="address" value="{{ old('address') ? old('address') : $request->input('player', '') }}" required>

                            @if ($errors->has('address'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('address') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('lifetime') ? ' has-error' : '' }}">
                            <label for="lifetime" class="control-label">Time Request</label>
                            <select class="form-control" id="lifetime" name="lifetime" required>
                                <option>Unlock for...</option>
                                <option value="60">1 hour</option>
                                <option value="1440">1 day</option>
                                <option value="10080">1 week</option>
                                <option value="43200">1 month</option>
                            </select>

                            @if ($errors->has('lifetime'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('lifetime') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="timestamp">Timestamp</label>
                            <input id="timestamp" type="text" class="form-control{{ $errors->has('timestamp') ? ' is-invalid' : '' }}" name="timestamp" value="{{ old('timestamp') ? old('timestamp') : \Carbon\Carbon::now() }}" required>

                            @if ($errors->has('timestamp'))
                                <div class="invalid-feedback">
                                     <strong>{{ $errors->first('timestamp') }}</strong>
                                </div>
                            @else
                                <small id="timestampHelp" class="form-text text-muted">Sign this timestamp to authorize time.</small>
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
                                Unlock Now
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection