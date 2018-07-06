<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdatePlayersRewards implements ShouldQueue
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
        try
        {
            // Get Reward Credits
            $credits = $this->getCredits();

            // Iterate Reward Credits
            foreach($credits as $credit)
            {
                // Non-Reward
                if($credit['quantity'] === 0) continue;

                // Get Player
                $player = \App\Player::whereAddress($credit['address'])->first();

                // Create Player Reward Relation
                $this->updateOrCreatePlayerReward($player, $credit);

                // Update Player Reward in Total
                $this->updatePlayerRewardsTotal($player);
            }

            // Handle Implicit Reward Last
            $this->handleImplicitReward();
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
    private function getCredits()
    {
        return $this->counterparty->execute('get_credits', [
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
    }

    /**
     * Update Or Create
     */
    private function updateOrCreatePlayerReward($player, $credit)
    {
        // Reward May Be Spread Across Multiple Credits
        if($player->rewards->contains($this->reward->id))
        {
            // Update Reward With Credit
            return $this->updatePlayerReward($player, $credit);
        }
        else
        {
            // Create Reward From Credit
            return $this->createPlayerReward($player, $credit);
        }
    }

    /**
     * Create Reward
     */
    private function createPlayerReward($player, $credit)
    {
        return $player->rewards()->save($this->reward, [
            'group_id' => $player->group_id,
            'total' => $credit['quantity'],
            'dry' => $player->isDAAB(),
        ]);
    }

    /**
     * Update Reward
     */
    private function updatePlayerReward($player, $credit)
    {
        $player_reward = $player->rewards()
            ->where('reward_id', '=', $this->reward->id)
            ->first();

        return $player_reward->update([
            'pivot.total' => $player_reward->pivot->total + $credit['quantity']
        ]);
    }

    /**
     * Model Method?
     */
    private function updatePlayerRewardsTotal($player)
    {
        return $player->update([
            'rewards_total' => $player->rewards()->sum('player_reward.total'),
        ]);
    }

    /**
     * Genesis Farm
     */
    private function handleImplicitReward()
    {
        $genesis = \App\Player::whereAddress(env('GENESIS_ADDRESS'))->first();

        return $genesis->rewards()->save($this->reward, [
            'group_id' => $genesis->group_id,
            'total' => $this->reward->total - $this->reward->players->sum('pivot.total'),
            'dry' => $genesis->isDAAB(),
        ]);
    }
}