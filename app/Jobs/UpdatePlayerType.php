<?php

namespace App\Jobs;

use JsonRPC\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdatePlayerType implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $counterparty;
    protected $player;
    protected $token;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(\App\Player $player, \App\Token $token)
    {
        $this->counterparty = new Client(env('CP_API'));
        $this->counterparty->authentication(env('CP_USER'), env('CP_PASS'));
        $this->player = $player;
        $this->token = $token;
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
                    'value' => $this->token->name,
                ],[
                    'field' => 'address',
                    'op'    => '==',
                    'value' => $this->player->address,
                ],
            ],
        ]);

        $tx = \App\Tx::whereTxHash($credits[0]['event'])->first();

        $this->player->update([
            'tx_id' => $tx->id,
            'type' => $credits[0]['calling_function'],
            'name' => $this->getGeneratedName($credits[0]['calling_function']),
            'processed_at' => \Carbon\Carbon::now(),
        ]);
    }

    private function getGeneratedName($type)
    {
        if('issuance' === $type)
        {
            return 'Genesis Farm';
        }

        return 'Bitcorn Farm #' . \App\Tx::has('player')->count();
    }
}
