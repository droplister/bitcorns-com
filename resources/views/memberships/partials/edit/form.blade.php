@if($player->group_id)
<form role="form" method="POST" action="{{ url(route('memberships.destroy', ['player' => $player->address])) }}">
    <input type="hidden" name="_method" value="DELETE">
    {{ csrf_field() }}
    <div class="form-group">
        <label for="name">Member of:</label>
        <select class="form-control" disabled> 
            <option>{{ $player->group->name }}</option>
        </select>
        @if($errors->has('name'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('name') }}</strong>
        </div>
        @endif
    </div>
    <div class="form-check">
        <input type="checkbox" name="leave" id="leave" required>
        <label for="leave">Please remove me from this group.</label>
        @if($errors->has('leave'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('leave') }}</strong>
        </div>
        @endif
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
@else
<p><a href="{{ url(route('groups.index')) }}">Join a cooperative</a> or <a href="{{ url(route('groups.create')) }}">start your own</a>!</p>
@endif