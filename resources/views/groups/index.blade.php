@extends('layouts.app')

@section('title', 'Farming Coops')

@section('content')
<div class="container">
    <a class="btn btn-primary float-right mt-3" href="{{ url(route('groups.create')) }}">
        Create
    </a>
    <h1 class="display-4 mt-5 mb-5">
        <span class="d-none d-sm-inline">Farming</span> Coops
        <small class="lead d-none d-sm-inline">{{ $groups->count() }} Found</small>
    </h1>
    @include('partials.session')
    <div class="card text-center">
        <div class="table-responsive">
            <table class="table mb-0 text-left">
                <thead>
                    <tr>
                        <th style="min-width: 200px">Name</th>
                        <th class="d-none d-sm-block">Description</th>
                        <th>Farms</th>
                        <th>Crops</th>
                        <th>Harvested</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($groups as $group)
                    <tr>
                        <th><a href="{{ url(route('groups.show', ['group' => $group->slug])) }}">{{ $group->name }}</a></th>
                        <td class="d-none d-sm-block">{{ $group->description }}</td>
                        <td>{{ $group->players_count }}</td>
                        <td>{{ $group->accessBalance() }}</td>
                        <td>{{ $group->rewards->sum('pivot.total') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
