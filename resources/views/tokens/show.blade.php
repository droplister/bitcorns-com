@extends('layouts.app')

@section('title', $token->long_name ? $token->long_name : $token->name)

@section('description', $token->content)

@section('content')
<div class="container">
    <h1 class="display-4 mt-5 mb-5">
        {{ $token->long_name ? $token->long_name : $token->name }} <small class="lead d-none d-sm-inline">{{ ucfirst($token->type) }} Token</small>
        <img src="{{ $token->display_thumb_url }}" class="float-left mr-3" height="60" />
    </h1>
    <div class="card mb-4">
        <div class="card-header">
            Description
        </div>
        <div class="card-body">
            <p class="card-text">{{ $token->content }}</p>
            <div class="row">
                <div class="col-sm-2 col-4">
                    <i class="{{ $token->divisible ? 'fa fa-check text-success' : 'fa fa-times text-danger' }}"></i> Divisible
                </div>
                <div class="col-sm-2 col-4">
                    <i class="{{ $token->locked ? 'fa fa-check text-success' : 'fa fa-times text-danger' }}"></i> Locked
                </div>
                <div class="col-sm-2 col-4">
                    <a href="{{ $token->explorer_url }}" class="card-link" target="_blank"><i class="fa fa-external-link-square"></i> Explorer</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <div class="card mb-4">
                <div class="card-header">
                    Supply
                </div>
                <div class="card-body">
                    @if('reward' === $token->type)
                    <p class="display-4 mb-0">{{ number_format($token->normalized_supply) }}</p>
                    <small class="lead">Cap: {{ $token->divisible ? number_format($token->display_total) : $token->display_total }}</small>
                    @else
                    <p class="display-4">{{ $token->normalized_supply }}</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card mb-4">
                <div class="card-header">
                    Holders
                </div>
                <div class="card-body">
                    <p class="display-4">{{ $holders_count }}</p>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card mb-4">
                <div class="card-header">
                    Transactions
                </div>
                <div class="card-body">
                    <p class="display-4">{{ $txs_count }}</p>
                </div>
            </div>
        </div>
    </div>
    @if($holders_count)
    <div class="card mb-4">
        <div class="card-header">
            Leaderboard
        </div>
        <div class="table-responsive">
            <table class="table mb-0">
                <tbody>
                    @foreach($holders as $holder)
                    <tr>
                        <th scope="row" class="pl-4">{{ $loop->iteration }}.</th>
                        <td><a href="{{ $holder->player->url }}">{{ $holder->player->name }}</a></td>
                        <td class="text-muted d-none d-sm-block"><small>{{ $holder->player->address }}</small></td>
                        <td class="text-right">{{ $holder->display_quantity }} {{ $holder->token->name }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
