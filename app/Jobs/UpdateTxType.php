<?php

namespace App\Jobs;

use JsonRPC\Client;
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
        $this->counterparty = new Client(env('CP_API'));
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
        // TODO: Optimize

        if('dividend' === $this->type)
        {
            foreach($this->getDividends($this->token) as $order)
            {
                $this->updateRecentTx($this->firstOrCreateTx($this->token, $this->type, $dividend));
            }
        }

        if('issuance' === $this->type)
        {
            foreach($this->getIssuances($this->token) as $issuance)
            {
                $this->updateRecentTx($this->firstOrCreateTx($this->token, $this->type, $issuance));
            }
        }

        if('order' === $this->type)
        {
            foreach($this->getOrders($this->token) as $order)
            {
                $this->updateRecentTx($this->firstOrCreateTx($this->token, $this->type, $order));
            }
        }

        if('send' === $this->type)
        {
            foreach($this->getSends($this->token) as $send)
            {
                $this->updateRecentTx($this->firstOrCreateTx($this->token, $this->type, $send));
            }
        }
    }

    private function firstOrCreateTx($token, $type, $tx)
    {
        return \App\Tx::firstOrCreate([
            'token_id' => $token->id,
            'type' => $type,
            'block_index' => $tx['block_index'],
            'tx_index' => $tx['tx_index'],
            'tx_hash' => $tx['tx_hash'],
        ]);
    }

    private function updateRecentTx($tx)
    {
        if($tx->wasRecentlyCreated) {
            \App\Jobs\UpdateTx::dispatch($tx);
        }
    }

    private function getDividends($token)
    {
        $gets = $this->counterparty->execute('get_dividends', [
            'filters' => [
                [
                    'field' => 'asset',
                    'op'    => '==',
                    'value' => $token->name,
                ],[
                    'field' => 'status',
                    'op'    => '==',
                    'value' => 'valid',
                ],
            ],
        ]);

        $gives = $this->counterparty->execute('get_dividends', [
            'filters' => [
                [
                    'field' => 'dividend_asset',
                    'op'    => '==',
                    'value' => $token->name,
                ],[
                    'field' => 'status',
                    'op'    => '==',
                    'value' => 'valid',
                ],
            ],
        ]);

        return array_merge($gives, $gets);
    }

    private function getIssuances($token)
    {
        return $this->counterparty->execute('get_issuances', [
            'filters' => [
                [
                    'field' => 'asset',
                    'op'    => '==',
                    'value' => $token->name,
                ],[
                    'field' => 'status',
                    'op'    => '==',
                    'value' => 'valid',
                ],
            ],
        ]);
    }

    private function getOrders($token)
    {
        $gives = $this->counterparty->execute('get_orders', [
            'filters' => [
                [
                    'field' => 'give_asset',
                    'op'    => '==',
                    'value' => $token->name,
                ],[
                    'field' => 'status',
                    'op'    => '!=',
                    'value' => 'invalid',
                ],
            ],
        ]);

        $gets = $this->counterparty->execute('get_orders', [
            'filters' => [
                [
                    'field' => 'get_asset',
                    'op'    => '==',
                    'value' => $token->name,
                ],[
                    'field' => 'status',
                    'op'    => '!=',
                    'value' => 'invalid',
                ],
            ],
        ]);

        return array_merge($gives, $gets);
    }

    private function getSends($token)
    {
        return $this->counterparty->execute('get_sends', [
            'filters' => [
                [
                    'field' => 'asset',
                    'op'    => '==',
                    'value' => $token->name,
                ],[
                    'field' => 'status',
                    'op'    => '==',
                    'value' => 'valid',
                ],
            ],
        ]);
    }
}
