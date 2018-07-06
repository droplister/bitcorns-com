@extends('layouts.app')

@section('title', 'Uploads')

@section('meta')
    <meta name="robots" content="noindex,nofollow">
@endsection

@section('content')
<div class="container">
    @include('uploads.partials.index.head')
    @include('uploads.partials.index.body')
</div>
@endsection