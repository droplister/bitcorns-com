@extends('layouts.app')

@section('title', 'Game Tokens')

@section('description', 'Guide to the different types of in-game tokens used on the Bitcorns.com blockchain game platform.')

@section('content')
<div class="container">
    <h1 class="display-4 mt-5 mb-5">
        Game Tokens <small class="lead">{{ $tokens->count() }} Found</small>
    </h1>
    @foreach($tokens as $token)
    <div class="card mb-4">
        <div class="card-header">
            {{ ucfirst($token->type) }} Token
        </div>
        <div class="card-body">
            <h4 class="card-title">
                <a href="{{ $token->url }}">
                    {{ $token->name }}
                    <img class="float-left mr-2" src="{{ $token->display_thumb_url }}" alt="{{ $token->name }} Icon" height="30" width="30" />
                </a>
            </h4>
            <p class="card-text">{{ $token->content }}</p>
            <a href="{{ $token->url }}" class="card-link"><i class="fa fa-info-circle"></i> More Info</a>
            <a href="{{ $token->explorer_url }}" class="card-link" target="_blank"><i class="fa fa-external-link-square"></i> Explorer</a>
        </div>
    </div>
    @endforeach
</div>
@endsection
