@extends('layouts.app')

@section('title', 'Card Directory')
@section('description', 'Guide to the different types of in-game cards used on the Bitcorns.com blockchain game platform.')

@section('content')
<div class="container">
    @include('cards.partials.index.head')
    @include('cards.partials.index.body')
</div>
@endsection
