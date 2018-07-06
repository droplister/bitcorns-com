<?php

namespace App\Listeners;

use App\Events\BalanceWasUpdated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateTokensBurned
{
    /**
     * Handle the event.
     */
    public function onUpdatedOrCreated($event)
    {
        if($event->balance->player->address === env('BURN_ADDRESS'))
        {
            $event->balance->token->update([
                'total_burned' => $event->balance->quantity,
            ]);
        }
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     * @return array
     */
    public function subscribe($events){
        $events->listen(
            'App\Events\BalanceWasCreated',
            'App\Listeners\UpdateTokensBurned@onUpdatedOrCreated'
        );

        $events->listen(
            'App\Events\BalanceWasUpdated',
            'App\Listeners\UpdateTokensBurned@onUpdatedOrCreated'
        );
    }
}