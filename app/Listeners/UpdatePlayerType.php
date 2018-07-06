<?php

namespace App\Listeners;

use App\Events\PlayerWasCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdatePlayerType
{
    /**
     * Handle the event.
     *
     * @param  PlayerWasCreated  $event
     * @return void
     */
    public function handle(PlayerWasCreated $event)
    {
        // Unprocessed Players Only
        if(! $event->player->processed_at)
        {
            \App\Jobs\UpdatePlayerType::dispatch($event->player);
        }
    }
}
