<?php

namespace App\Jobs;

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
        $this->counterparty = new \JsonRPC\Client(env('CP_API'));
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
        try
        {
            // Get All Token Balances
            $balances = $this->getBalances();

            // Iterate Player Balances
            foreach($balances as $balance)
            {
                // Players Only
                if($player = \App\Player::whereAddress($balance['address'])->first())
                {
                    // Update Balance
                    $this->updateOrCreateBalance($player, $balance);
                }
            }
        }
        catch(\Exception $e)
        {
            // API 404
        }
    }

    /**
     * Counterparty API
     * https://counterparty.io/docs/api/#get_table
     */
    private function getBalances()
    {
        return $this->counterparty->execute('get_balances', [
            'filters' => [
                'field' => 'asset',
                'op'    => '==',
                'value' => $this->token->name,
            ],
        ]);
    }

    /**
     * Update Balance
     */
    private function updateOrCreateBalance($player, $balance)
    {
        return \App\Balance::updateOrCreate([
            'player_id' => $player->id,
            'token_id' => $this->token->id,
        ],[
            'quantity' => $balance['quantity'],
        ]);
    }
}
