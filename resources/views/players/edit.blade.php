@extends('layouts.app')

@section('title', $player->name)
@section('description', 'Edit Bitcorn Farm')

@section('meta')
    <meta name="robots" content="noindex,nofollow">
@endsection

@section('content')
    @include('players.partials.edit.head')
    @include('players.partials.edit.body')
@endsection