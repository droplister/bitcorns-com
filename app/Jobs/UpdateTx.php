<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateTx implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $counterparty;
    protected $tx;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(\App\Tx $tx)
    {
        $this->counterparty = new \JsonRPC\Client(env('CP_API'));
        $this->counterparty->authentication(env('CP_USER'), env('CP_PASS'));
        $this->tx = $tx;
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
            // Get Raw Transaction
            $rt_data = $this->getRawTx();

            // Get Transaction Info
            $tx_data = $this->getTxInfo($rt_data);

            // Update Transaction
            $this->updateTx($rt_data, $tx_data);
        }
        catch(\Exception $e)
        {
            // API 404
        }
    }

    /**
     * Counterparty API
     * https://counterparty.io/docs/api/#getrawtransaction
     */
    private function getRawTx()
    {
        return $this->counterparty->execute('getrawtransaction', [
            'tx_hash' => $this->tx->tx_hash,
            'verbose' => true,
        ]);
    }

    /**
     * Counterparty API
     * https://counterparty.io/docs/api/#get_tx_info
     */
    private function getTxInfo($rt_data)
    {
        return $this->counterparty->execute('get_tx_info', [
            'tx_hex' => $rt_data['hex'],
            'block_index' => $this->tx->block_index,
        ]);
    }

    /**
     * Update Tx
     */
    private function updateTx($rt_data, $tx_data)
    {
        return $this->tx->update([
            'source' => $tx_data[0],
            'destination' => $tx_data[1],
            'quantity' => is_null($tx_data[2]) ? 0 : $tx_data[2],
            'fee' => $tx_data[3],
            'tx_hex' => $rt_data['hex'],
            'confirmed_at' => isset($rt_data['time']) ? \Carbon\Carbon::createFromTimestampUTC($rt_data['time']) : null,
            'processed_at' => \Carbon\Carbon::now(),
        ]);
    }
}
