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
        // Get Tokens
        $access_token = \App\Token::whereType('access')->first();
        $reward_token = \App\Token::whereType('reward')->first();

        try
        {
            // Get Rewards
            $rewards = $this->getRewards($access_token, $reward_token);

            // Iterate Rewards
            foreach($rewards as $reward)
            {
                // Create Reward
                $this->firstOrCreateReward($access_token, $reward_token, $reward);
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
    private function getRewards($access_token, $reward_token)
    {
        return $this->counterparty->execute('get_dividends', [
            'filters' => [
                [
                    'field' => 'source',
                    'op'    => '==',
                    'value' => $access_token->issuer,
                ],[
                    'field' => 'asset',
                    'op'    => '==',
                    'value' => $access_token->name,
                ],[
                    'field' => 'dividend_asset',
                    'op'    => '==',
                    'value' => $reward_token->name,
                ],[
                    'field' => 'status',
                    'op'    => '==',
                    'value' => 'valid',
                ],
            ],
        ]);
    }

    /**
     * Create Reward
     */
    private function firstOrCreateReward($access_token, $reward_token, $reward)
    {
        $tx = \App\Tx::whereTxHash($reward['tx_hash'])->first();
        $per_token = fromSatoshi($reward['quantity_per_unit']);
        $total = fromSatoshi($access_token->total_issued) * $per_token;

        return \App\Reward::firstOrCreate([
            'token_id' => $reward_token->id,
            'tx_id' => $tx->id,
        ],[
            'per_token' => $per_token,
            'total' => $total,
        ]);
    }
}
