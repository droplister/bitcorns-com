@extends('layouts.app')

@section('title', 'Farming Coops')

@section('content')
<div class="container">
    @include('groups.partials.index.head')
    @include('groups.partials.index.body')
</div>
@endsection
