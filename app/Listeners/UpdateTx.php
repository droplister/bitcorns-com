<?php

namespace App\Listeners;

use App\Events\TxWasCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateTx
{
    /**
     * Handle the event.
     *
     * @param  TxWasCreated  $event
     * @return void
     */
    public function handle(TxWasCreated $event)
    {
        // Unprocessed Txs Only
        if(! $event->tx->processed_at)
        {
            \App\Jobs\UpdateTx::dispatch($event->tx);
        }
    }
}
