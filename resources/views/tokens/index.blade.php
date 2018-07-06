@extends('layouts.app')

@section('title', 'Game Tokens')
@section('description', 'Guide to the different types of in-game tokens used on the Bitcorns.com blockchain game platform.')

@section('content')
<div class="container">
    @include('tokens.partials.index.head')
    @include('tokens.partials.index.body')
</div>
@endsection
