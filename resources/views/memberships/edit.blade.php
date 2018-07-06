@extends('layouts.app')

@section('title', $player->name)

@section('meta')
    <meta name="robots" content="noindex,nofollow">
@endsection

@section('content')
    @include('players.partials.edit.head')
    @include('memberships.partials.edit.body')
@endsection