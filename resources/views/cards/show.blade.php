@extends('layouts.app')

@section('title', $card->display_name)
@section('description', $card->content)

@section('content')
<div class="container">
    @include('cards.partials.show.head')
    @include('cards.partials.show.body')
</div>
@endsection
