<form role="form" method="POST" action="{{ url(route('uploads.store', ['player' => $player->address])) }}" enctype="multipart/form-data">
    {{ csrf_field() }}
    <div class="form-group">
        <input type="file" name="image" accept="image/jpeg,image/gif" class="{{ $errors->has('image') ? 'is-invalid' : '' }}" />
        @if($errors->has('image'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('image') }}</strong>
        </div>
        @else
        <small id="imageHelp" class="form-text text-muted">1600x900 pixels. (Maximum: 2.5 MB)</small>
        @endif
    </div>
    <h4>Guidelines:</h4>
    <ul class="mb-4">
        <li>Must be a depiction of a place or scene, i.e. "my farm looks like this."</li>
        <li>JPEG files only, at this time.</li>
    </ul>
    @if(Auth::guard('player')->check() && Auth::guard('player')->user()->address === $player->address)
    <small class="text-muted"><em>Your farm is currently unlocked, so you can save your changes without further message signing.</em></small>
    <br />
    <br />
    @else
    <hr class="mb-4" />
    <div class="form-group">
        <label for="timestamp">Timestamp</label>
        <input id="timestamp" type="text" class="form-control{{ $errors->has('timestamp') ? ' is-invalid' : '' }}" name="timestamp" value="{{ old('timestamp') ? old('timestamp') : \Carbon\Carbon::now() }}" required>
        @if($errors->has('timestamp'))
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