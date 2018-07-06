<?php

namespace App\Listeners;

use App\Events\BalanceWasUpdated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NullifyCoordinates
{
    /**
     * Handle the event.
     */
    public function onUpdatedOrCreated($event)
    {
        if($event->balance->token->type === 'access' &&
           $event->balance->quantity === 0)
        {
            $event->balance->player->update([
                'latitude' => null,
                'longitude' => null,
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
            'App\Listeners\NullifyCoordinates@onUpdatedOrCreated'
        );

        $events->listen(
            'App\Events\BalanceWasUpdated',
            'App\Listeners\NullifyCoordinates@onUpdatedOrCreated'
        );
    }
}
