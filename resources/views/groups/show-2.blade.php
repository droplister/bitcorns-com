@extends('layouts.app')

@section('title', $group->name)

@section('content')
<div class="container">
    @include('groups.partials.show.head')
    @include('groups.partials.show.body')
</div>
@endsection