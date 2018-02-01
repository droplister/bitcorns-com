<?php

namespace App\Jobs;

use JsonRPC\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateRewards implements ShouldQueue
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
        $access_token = \App\Token::whereType('access')->first();

        $rewards = $this->counterparty->execute('get_dividends', [
            'filters' => [
                'field' => 'asset',
                'op'    => '==',
                'value' => $access_token->name,
            ],
        ]);

        foreach($rewards as $this_reward)
        {
            if($this_reward['source'] !== $access_token->issuer || $this_reward['dividend_asset'] !== $this->token->name || $this_reward['status'] !== 'valid') continue;

            $tx = \App\Tx::whereTxHash($this_reward['tx_hash'])->first();

            $reward = \App\Reward::firstOrCreate([
                'token_id' => $this->token->id,
                'tx_id' => $tx->id,
                'per_token' => $this_reward['quantity_per_unit'],
            ]);

            if($reward->wasRecentlyCreated)
            {
                \App\Jobs\UpdatePlayersRewardTotal::dispatch($reward);
            }
        }
    }
}
