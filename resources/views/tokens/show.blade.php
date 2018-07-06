@extends('layouts.app')

@section('title', $token->display_name)
@section('description', $token->content)

@section('content')
<div class="container">
    @include('tokens.partials.show.head')
    @include('tokens.partials.show.body')
</div>
@endsection
