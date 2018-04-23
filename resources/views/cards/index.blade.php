@extends('layouts.app')

@section('title', 'Card Directory')

@section('description', 'Guide to the different types of in-game cards used on the Bitcorns.com blockchain game platform.')

@section('content')
<div class="container">
    <h1 class="display-4 mt-5 mb-5">
        <span class="d-none d-sm-inline">Bitcorn</span> Cards <small class="lead">{{ $cards->count() }} Found</small>
    </h1>
    <div class="row">
        @foreach($cards as $card)
        <div class="col-4 mb-4 text-center">
            <h4 class="card-title d-none d-md-block">{{ $card->display_name }}</h4>
            <p class="card-text d-none d-md-block">Issuance: {{ $card->normalized_total }}</p>
            <a href="{{ url(route('cards.show', ['token' => $card->name])) }}"><img src="{{ str_replace('tokens', 'cards', $card->image_url) }}" width="100%" /></a>
        </div>
        @endforeach
    </div>
</div>
@endsection
