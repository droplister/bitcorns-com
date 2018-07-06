<form role="form" method="POST" action="{{ url(route('players.update', ['player' => $player->address])) }}">
    <input type="hidden" name="_method" value="PUT">
    {{ csrf_field() }}
    <div class="form-group">
        <label for="name">Name</label>
        <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') ? old('name') : $player->name }}">
        @if ($errors->has('name'))
            <div class="invalid-feedback">
                 <strong>{{ $errors->first('name') }}</strong>
            </div>
        @endif
    </div>
    <div class="form-group">
        <label for="description">Description</label>
        <input id="description" type="text" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" name="description" value="{{ old('description') ? old('description') : $player->description }}">
        @if ($errors->has('description'))
            <div class="invalid-feedback">
                 <strong>{{ $errors->first('description') }}</strong>
            </div>
        @endif
    </div>
    <div class="row">
        <div class="form-group col-sm-6">
            <label for="latitude">Latitude</label>
            <input id="latitude" type="text" class="form-control{{ $errors->has('latitude') ? ' is-invalid' : '' }}" name="latitude" value="{{ old('latitude') ? old('latitude') : $player->latitude }}">
            @if ($errors->has('latitude'))
                <div class="invalid-feedback">
                     <strong>{{ $errors->first('latitude') }}</strong>
                </div>
            @endif
        </div>
        <div class="form-group col-sm-6">
            <label for="longitude">Longitude</label>
            <input id="longitude" type="text" class="form-control{{ $errors->has('longitude') ? ' is-invalid' : '' }}" name="longitude" value="{{ old('longitude') ? old('longitude') : $player->longitude }}">
            @if ($errors->has('longitude'))
                <div class="invalid-feedback">
                     <strong>{{ $errors->first('longitude') }}</strong>
                </div>
            @endif
        </div>
    </div>
    @if(Auth::guard('player')->check() && Auth::guard('player')->user()->address === $player->address)
        <small class="text-muted"><em>Your farm is currently unlocked, so you can save your changes without further message signing.</em></small>
        <br />
        <br />
    @else
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
    @endif
    <div class="form-group">
        <button type="submit" class="btn btn-primary">
            <i class="fa fa-save"></i> Save
        </button>
    </div>
</form>