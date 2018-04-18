<?php

namespace App\Jobs;

use JsonRPC\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateBalances implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $counterparty;
    protected $token;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(\App\Token $token)
    {
        $this->counterparty = new Client(env('CP_API'));
        $this->counterparty->authentication(env('CP_USER'), env('CP_PASS'));
        $this->token = $token;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $balances = $this->counterparty->execute('get_balances', [
            'filters' => [
                'field' => 'asset',
                'op'    => '==',
                'value' => $this->token->name,
            ],
        ]);

        foreach($balances as $balance)
        {
            if($player = \App\Player::whereAddress($balance['address'])->first())
            {
                \App\Balance::updateOrCreate([
                    'player_id' => $player->id,
                    'token_id' => $this->token->id,
                ],[
                    'quantity' => $balance['quantity'],
                ]);

                if($this->token->type == 'access' && $balance['quantity'] == 0)
                {
                    $player->update(['latitude' => null, 'longitude' => null]);
                }

                if($player->address === env('BURN_ADDRESS'))
                {
                    $this->token->update(['total_burned' => $balance['quantity']]);
                }
            }
        }
    }
}
