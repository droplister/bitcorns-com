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
                <p class="display-4">{{ $players->total() }}</p>
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