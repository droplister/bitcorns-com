<div class="row">
    <div class="col-md-3">
        <div class="mb-4">
            <img src="{{ str_replace('tokens', 'cards', $card->image_url) }}" width="100%" style="max-width: 375px;" />
        </div>
    </div>
    <div class="col-md-9">
        <div class="card mb-4">
            <div class="card-header">
                Description
            </div>
            <div class="card-body">
                <p class="card-text">{{ $card->content }}</p> 
                <div class="row">
                    <div class="col-4 col-sm-3 col-lg-2">
                        <i class="{{ $card->divisible ? 'fa fa-check text-success' : 'fa fa-times text-danger' }}"></i> Divisible
                    </div>
                    <div class="col-4 col-sm-3 col-lg-2">
                        <i class="{{ $card->locked ? 'fa fa-check text-success' : 'fa fa-times text-danger' }}"></i> Locked
                    </div>
                    <div class="col-4 col-sm-3 col-lg-2">
                        <a href="{{ $card->explorer_url }}" class="card-link" target="_blank"><i class="fa fa-external-link-square"></i> Explorer</a>
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
                        @if('reward' === $card->type)
                        <p class="card-text">{{ number_format($card->normalized_supply) }}</p>
                        <small class="lead">Cap: {{ $card->divisible ? number_format($card->display_total) : $card->display_total }}</small>
                        @else
                        <p class="card-text">{{ $card->normalized_supply }}</p>
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
                        <p class="card-text">{{ $players->total() }}</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card mb-4">
                    <div class="card-header">
                        Transactions
                    </div>
                    <div class="card-body">
                        <p class="card-text">{{ $txs_count }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>