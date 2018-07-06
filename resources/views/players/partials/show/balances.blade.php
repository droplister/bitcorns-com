<div class="row mt-5">
    <div class="col">
        <div class="card text-center">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" id="activityTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="activity-tab" data-toggle="tab" href="#activity" role="tab" aria-controls="activity" aria-selected="true">Wallet</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="activityTabContent">
                    <div class="tab-pane fade show active" id="activity" role="tabpanel" aria-labelledby="activity-tab">
                        <div class="row mt-1 mb-2 text-left">
                            @foreach($balances as $balance)
                            <div class="col-xs-12 col-sm-6 col-md-4 mb-2">
                                <a href="{{ url(route('tokens.show', ['token' => $balance->token->display_name])) }}"><img src="{{ $balance->token->thumb_url }}" class="float-left mr-3" /></a>
                                <h4 class="card-title"><a href="{{ url(route('tokens.show', ['token' => $balance->token->display_name])) }}" class="text-dark">{{ $balance->token->display_name }}</a></h4>
                                <p class="card-text">{{ $balance->display_quantity }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table mb-0 text-left" style="overflow-y: auto;white-space: nowrap;">
                    <thead>
                        <tr>
                            <th>Activity</th>
                            <th>Token</th>
                            <th>Record</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($txs as $tx)
                        <tr>
                            <th class="text-capitalize">{{ $tx->type }}</th>
                            <td><a href="{{ $tx->token->url }}">{{ $tx->token->name }}</a></td>
                            <td class="text-muted">{{ $tx->tx_hash }}</td>
                            <td>{{ $tx->display_confirmed_at }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>