<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateRewards implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $counterparty;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->counterparty = new \JsonRPC\Client(env('CP_API'));
        $this->counterparty->authentication(env('CP_USER'), env('CP_PASS'));
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // TODO: Use token .env variables?
        $access_token = \App\Token::whereType('access')->first();
        $reward_token = \App\Token::whereType('reward')->first();

        $rewards = $this->counterparty->execute('get_dividends', [
            'filters' => [
                'field' => 'asset',
                'op'    => '==',
                'value' => $access_token->name,
            ],
        ]);

        foreach($rewards as $this_reward)
        {
            // Sanity Checks
            if($this_reward['source'] !== $access_token->issuer || $this_reward['dividend_asset'] !== $reward_token->name || $this_reward['status'] !== 'valid') continue;

            $tx = \App\Tx::whereTxHash($this_reward['tx_hash'])->first();

            $reward = \App\Reward::firstOrCreate([
                'token_id' => $reward_token->id,
                'tx_id' => $tx->id,
            ],[
                'total' => fromSatoshi($access_token->total_issued) * fromSatoshi($this_reward['quantity_per_unit']),
                'per_token' => fromSatoshi($this_reward['quantity_per_unit']),
            ]);

            if($reward->wasRecentlyCreated)
            {
                \App\Jobs\UpdatePlayersRewardsTotal::dispatch($reward);
            }
        }
    }
}
