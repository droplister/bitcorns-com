<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateTxType implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $counterparty;
    protected $token;
    protected $type;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(\App\Token $token, $type)
    {
        $this->counterparty = new \JsonRPC\Client(env('CP_API'));
        $this->counterparty->authentication(env('CP_USER'), env('CP_PASS'));
        $this->token = $token;
        $this->type = $type;
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
            $offset = $this->getOffset();

            while($offset <= 10000000)
            {
                $txs = $this->getTxs($offset);

                foreach($txs as $tx)
                {
                    $this->firstOrCreateTx($tx, $offset);
                }

                $offset = $offset + 1000;

                if(count($txs) === 0) break;
            }
        }
        catch(\Exception $e)
        {
            // API 404
        }
    }

    /**
     * Get Offset
     */
    private function getOffset()
    {
        $tx = $this->token->txs()
            ->where('type', '=', $this->type)
            ->orderBy('offset', 'desc')
            ->first();

        return $tx ? $tx->offset : 0;
    }

    /**
     * Get Txs
     */
    private function getTxs($offset)
    {
        switch($this->type)
        {
            case 'dividend':
                return $this->getMergedDividends($offset);
            case 'issuance':
                return $this->getIssuances($offset);
            case 'order':
                return $this->getMergedOrders($offset);
            case 'send':
                return $this->getSends($offset);
            default:
                \Exception('No Type Set');
        }
    }

    /**
     * Create Tx
     */
    private function firstOrCreateTx($tx, $offset)
    {
        return \App\Tx::firstOrCreate([
            'tx_index' => $tx['tx_index'],
        ],[
            'token_id' => $this->token->id,
            'type' => $this->type,
            'block_index' => $tx['block_index'],
            'tx_index' => $tx['tx_index'],
            'tx_hash' => $tx['tx_hash'],
            'offset' => $offset,
        ]);
    }

    /**
     * Merged Dividends
     */
    private function getMergedDividends($offset)
    {
        $gives = $this->getDividends('dividend_asset', $offset);
        $gets = $this->getDividends('asset', $offset);

        return array_merge($gives, $gets);
    }

    /**
     * Counterparty API
     * https://counterparty.io/docs/api/#get_table
     */
    private function getDividends($field, $offset)
    {
        return $this->counterparty->execute('get_dividends', [
            'filters' => [
                [
                    'field' => $field,
                    'op'    => '==',
                    'value' => $this->token->name,
                ],[
                    'field' => 'status',
                    'op'    => '==',
                    'value' => 'valid',
                ],
            ],
            'offset' => $offset,
        ]);
    }

    /**
     * Counterparty API
     * https://counterparty.io/docs/api/#get_table
     */
    private function getIssuances($offset)
    {
        return $this->counterparty->execute('get_issuances', [
            'filters' => [
                [
                    'field' => 'asset',
                    'op'    => '==',
                    'value' => $this->token->name,
                ],[
                    'field' => 'status',
                    'op'    => '==',
                    'value' => 'valid',
                ],
            ],
            'offset' => $offset,
        ]);
    }

    /**
     * Merged Orders
     */
    private function getMergedOrders($offset)
    {
        $gives = $this->getOrders('give_asset', $offset);
        $gets = $this->getOrders('get_asset', $offset);

        return array_merge($gives, $gets);
    }

    /**
     * Counterparty API
     * https://counterparty.io/docs/api/#get_table
     */
    private function getOrders($field, $offset)
    {
        return $this->counterparty->execute('get_orders', [
            'filters' => [
                [
                    'field' => $field,
                    'op'    => '==',
                    'value' => $this->token->name,
                ],[
                    'field' => 'status',
                    'op'    => '!=',
                    'value' => 'invalid',
                ],
            ],
            'offset' => $offset,
        ]);
    }

    /**
     * Counterparty API
     * https://counterparty.io/docs/api/#get_table
     */
    private function getSends($offset)
    {
        return $this->counterparty->execute('get_sends', [
            'filters' => [
                [
                    'field' => 'asset',
                    'op'    => '==',
                    'value' => $this->token->name,
                ],[
                    'field' => 'status',
                    'op'    => '==',
                    'value' => 'valid',
                ],
            ],
            'offset' => $offset,
        ]);
    }
}
