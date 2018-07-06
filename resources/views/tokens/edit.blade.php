@extends('layouts.app')

@section('title', 'Edit Token')

@section('meta')
    <meta name="robots" content="noindex,nofollow">
@endsection

@section('content')
<div class="container">
    @include('tokens.partials.edit.head')
    @include('tokens.partials.edit.body')
</div>
@endsection
