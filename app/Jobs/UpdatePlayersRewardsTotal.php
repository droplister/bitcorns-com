<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdatePlayersRewardsTotal implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $counterparty;
    protected $reward;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(\App\Reward $reward)
    {
        $this->counterparty = new \JsonRPC\Client(env('CP_API'));
        $this->counterparty->authentication(env('CP_USER'), env('CP_PASS'));
        $this->reward = $reward;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /**
         * When a reward has been given, let's assume it will be
         * the only reward at this block height on this token
         * and update player reward amounts accordingly.
         */
        $credits = $this->counterparty->execute('get_credits', [
            'filters' => [
                [
                    'field' => 'asset',
                    'op'    => '==',
                    'value' => $this->reward->token->name,
                ], [
                    'field' => 'calling_function',
                    'op'    => '==',
                    'value' => 'dividend',
                ], [
                    'field' => 'block_index',
                    'op'    => '==',
                    'value' => $this->reward->tx->block_index,
                ],
            ],
        ]);

        /**
         * It is possible that a single player may receive their
         * reward as multiple credits. So, let's make sure 
         * we combine those credits for simplicity sake.
         */
        foreach($credits as $credit)
        {
            if($credit['quantity'] === 0) continue; // Skip "Non-Rewards"

            $player = \App\Player::whereAddress($credit['address'])->first();

            // Check for and handle existing reward relation
            if($player->rewards->contains($this->reward->id))
            {
                // Increment Existing
                $reward = $player->rewards()->whereRewardId($this->reward->id)->first();
                $reward->update([
                    'pivot.total' => $reward->pivot->total + $credit['quantity']
                ]);
            }
            else
            {
                // Create Relationship
                $player->rewards()->save($this->reward, [
                    'group_id' => $player->group_id,
                    'total' => $credit['quantity'],
                ]);
            }

            // Cache Total
            $player->update([
                'rewards_total' => $player->rewards()->sum('player_reward.total'),
            ]);
        }

        // Implicit Reward
        $genesis = \App\Player::whereAddress(env('GENESIS_ADDRESS'))->first();
        $genesis->rewards()->save($this->reward, [
            'group_id' => $genesis->group_id,
            'total' => $this->reward->total - $this->reward->players->sum('pivot.total'),
        ]);
    }
}