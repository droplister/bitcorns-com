<?php

namespace App\Listeners;

use App\Events\RewardWasCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdatePlayerRewards
{
    /**
     * Handle the event.
     *
     * @param  RewardWasCreated  $event
     * @return void
     */
    public function handle(RewardWasCreated $event)
    {
        \App\Jobs\UpdatePlayersRewards::dispatch($event->reward);
    }
}
