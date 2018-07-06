@extends('layouts.app')

@section('title', 'Start a Coop')

@section('meta')
    <meta name="robots" content="noindex,nofollow">
@endsection

@section('content')
<div class="container">
    @include('groups.partials.create.head')
    @include('groups.partials.create.body')
</div>
@endsection