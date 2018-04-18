<?php

namespace App\Jobs;

use JsonRPC\Client;
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
        $this->counterparty = new Client(env('CP_API'));
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

        foreach($credits as $credit)
        {
            $player = \App\Player::whereAddress($credit['address'])->first();

            if($credit['quantity'] === 0) continue;

            $player->rewards()->save($this->reward, [
                'group_id' => $player->group_id,
                'total' => $credit['quantity'],
            ]);

            $player->update([
                'rewards_total' => $player->rewards()->sum('player_reward.total'),
            ]);
        }
    }
}