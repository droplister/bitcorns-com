@extends('layouts.app')

@section('title', $player->name)

@section('content')
<section class="jumbotron text-center" style="background: url({{ $player->display_image_url }}) no-repeat center center / cover;">
    <a href="{{ url(route('players.show', ['player' => $player->address])) }}" class="btn btn-sm btn-light">
        <i class="fa fa-eye"></i> View
    </a>
</section>
<div class="content">
    <div class="container">
        @include('partials.session')
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url(route('players.edit', ['player' => $player->address])) }}" role="tab"><i class="fa fa-edit"></i> Profile</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url(route('uploads.store', ['player' => $player->address])) }}" role="tab"><i class="fa fa-photo"></i> Image</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="{{ url(route('memberships.edit', ['player' => $player->address])) }}" role="tab"><i class="fa fa-sitemap"></i> Co-Op</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <form role="form" method="POST" action="{{ url(route('players.update', ['player' => $player->address])) }}">
                            <input type="hidden" name="_method" value="PUT">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="name">Membership:</label>
                                <select class="form-control" disabled> 
                                    <option>Coming Soon</option>
                                </select>
                                @if ($errors->has('name'))
                                    <div class="invalid-feedback">
                                         <strong>{{ $errors->first('name') }}</strong>
                                    </div>
                                @endif
                            </div>
                            <div class="form-check">
                                <input type="checkbox" name="leave" id="leave" required>
                                <label for="leave">Please remove me from this group.</label>
                                @if ($errors->has('leave'))
                                    <div class="invalid-feedback">
                                         <strong>{{ $errors->first('leave') }}</strong>
                                    </div>
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
                                    <small id="signatureHelp" class="form-text text-muted">Enter your signed timestamp message. <a href="https://www.youtube.com/watch?v=Zne720b31Kk" target="_blank"><i class="fa fa-external-link"></i> Tutorial</a></small>
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