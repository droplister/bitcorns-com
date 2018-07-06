@extends('layouts.app')

@section('title', $player->display_name)
@section('description', $player->display_description)

@section('meta')
    <meta property="og:title" content="{{ $player->name }}" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ $player->url }}" />
    <meta property="og:image" content="{{ $player->display_image_url }}" />
@endsection

@section('content')
    @include('players.partials.show.head')
    @include('players.partials.show.body')
@endsection