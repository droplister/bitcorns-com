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

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(\App\Player $player)
    {
        $this->counterparty = new Client(env('CP_API'));
        $this->counterparty->authentication(env('CP_USER'), env('CP_PASS'));
        $this->player = $player;
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
                    'value' => env('ACCESS_TOKEN_NAME'),
                ],[
                    'field' => 'address',
                    'op'    => '==',
                    'value' => $this->player->address,
                ],
            ],
        ]);

        $tx_hashes = explode('_', $credits[0]['event']);

        if(count($tx_hashes) > 1)
        {
            foreach($tx_hashes as $tx_hash)
            {
                if($tx = \App\Tx::whereTxHash($tx_hash)->whereSource($this->player->address)->first())
                {
                    $this->player->update([
                        'tx_id' => $tx->id,
                        'type' => $credits[0]['calling_function'],
                        'name' => $this->getGeneratedName($credits[0]['calling_function'], $tx),
                        'processed_at' => \Carbon\Carbon::now(),
                    ]);
                }
            }
        }
        else
        {
            foreach($tx_hashes as $tx_hash)
            {
                if($tx = \App\Tx::whereTxHash($tx_hash)->first())
                {
                    $this->player->update([
                        'tx_id' => $tx->id,
                        'type' => $credits[0]['calling_function'],
                        'name' => $this->getGeneratedName($credits[0]['calling_function'], $tx),
                        'processed_at' => \Carbon\Carbon::now(),
                    ]);
                }
            }
        }
    }

    private function getGeneratedName($type, $tx)
    {
        if('issuance' === $type) return 'Genesis Farm';

        if('dividend' === $type)
        {
            $ties = num2alpha($tx->players->count() - 1);
        }

        $rank = \App\Tx::has('players')->count();

        return isset($ties) ? "Bitcorn Farm #{$rank}{$ties}" : "Bitcorn Farm #{$rank}";
    }
}
