@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mt-4 mb-4">{{ $token->name }}</h1>
    <form method="POST" action="{{ route('tokens.update', ['token' => $token->name]) }}">
        <input type="hidden" name="_method" value="PUT">
        {{ csrf_field() }}
        <div class="form-group{{ $errors->has('image_url') ? ' has-error' : '' }}">
            <label for="image_url" class="control-label">Image URL:</label>
            <input id="image_url" type="text" class="form-control" name="image_url" value="{{ old('image_url') ? old('image_url') : $token->image_url }}">

            @if ($errors->has('image_url'))
                <span class="help-block">
                    <strong>{{ $errors->first('image_url') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group{{ $errors->has('thumb_url') ? ' has-error' : '' }}">
            <label for="thumb_url" class="control-label">Thumb URL:</label>
            <input id="thumb_url" type="type" class="form-control" name="thumb_url" value="{{ old('thumb_url') ? old('thumb_url') : $token->thumb_url }}">

            @if ($errors->has('thumb_url'))
                <span class="help-block">
                    <strong>{{ $errors->first('thumb_url') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
            <label for="content" class="control-label">Content:</label>
            <textarea id="content" class="form-control" name="content" rows="10">{{ old('content') ? old('content') : $token->content }}</textarea>

            @if ($errors->has('content'))
                <span class="help-block">
                    <strong>{{ $errors->first('content') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-save"></i> Update
            </button>
        </div>
    </form>
</div>
@endsection
