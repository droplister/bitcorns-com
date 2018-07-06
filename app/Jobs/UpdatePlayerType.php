<?php

namespace App\Jobs;

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
        $this->counterparty = new \JsonRPC\Client(env('CP_API'));
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
        try
        {
            // First Access Token Credit
            $credit = $this->getCredit();

            // Multiple Hashes Are Possible
            $tx_hashes = explode('_', $credit['event']);

            // Determine Actual Creation Tx
            $creation_tx = $this->getCreationTx($tx_hashes);

            // Update Player Type and Name
            $this->updatePlayer($creation_tx, $credit['calling_function']);
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
    private function getCredit()
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

        return $credits[0];
    }

    /**
     * Creation TX
     */
    private function getCreationTx($tx_hashes)
    {
        $tx = $this->getTx($tx_hashes[0]);

        if(! $tx && isset($tx_hashes[1]))
        {
            $tx = $this->getTx($tx_hashes[1]);
        }

        return $tx;
    }

    /**
     * Get Tx
     */
    private function getTx($tx_hash)
    {
        return \App\Tx::where('source', '=', $this->player->address)
            ->where('tx_hash', '=', $tx_hash)
            ->first();
    }

    /**
     * Update Player
     */
    private function updatePlayer($creation_tx, $type)
    {
        $name = $this->getGeneratedName($type, $creation_tx);

        return $this->player->update([
            'tx_id' => $creation_tx->id,
            'type' => $type,
            'name' => $name,
            'processed_at' => \Carbon\Carbon::now(),
        ]);
    }

    /**
     * Generated Name
     */
    private function getGeneratedName($type, $creation_tx)
    {
        if('issuance' === $type) return 'Genesis Farm';

        if('dividend' === $type)
        {
            $ties = num2alpha($creation_tx->players()->count() - 1);
        }

        $rank = \App\Tx::has('players')->count();

        return isset($ties) ? "Bitcorn Farm #{$rank}{$ties}" : "Bitcorn Farm #{$rank}";
    }
}