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

        $offset = $this->getOffset($this->type, $this->token);

        while($offset <= 10000000)
        {
            switch($this->type) {
                case 'dividend':
                    $txs = $this->getDividends($this->token, $offset);
                    break;
                case 'issuance':
                    $txs = $this->getIssuances($this->token, $offset);
                    break;
                case 'order':
                    $txs = $this->getOrders($this->token, $offset);
                    break;
                case 'send':
                    $txs = $this->getSends($this->token, $offset);
                    break;
                default:
                    \Exception('No Type Set');
                    break;
            }

            if(! count($txs)) break;

            foreach($txs as $tx)
            {
                $this->updateRecentTx($this->firstOrCreateTx($this->token, $this->type, $tx, $offset));
            }

            $offset = $offset + 1000;
        }
    }

    private function getOffset($type, $token)
    {
        $tx = \App\Tx::whereType($type)->with(['token' => function ($query) use ($token) {
            $query->where('name', '=', $token->name);
        }])->orderBy('offset', 'desc')->first();

        return $tx ? $tx->offset : 0;
    }

    private function firstOrCreateTx($token, $type, $tx, $offset)
    {
        return \App\Tx::firstOrCreate([
            'tx_index' => $tx['tx_index'],
        ],[
            'token_id' => $token->id,
            'offset' => $offset,
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

    private function getDividends($token, $offset = 0)
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
            'offset' => $offset,
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
            'offset' => $offset,
        ]);

        return array_merge($gives, $gets);
    }

    private function getIssuances($token, $offset = 0)
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
            'offset' => $offset,
        ]);
    }

    private function getOrders($token, $offset = 0)
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
            'offset' => $offset,
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
            'offset' => $offset,
        ]);

        return array_merge($gives, $gets);
    }

    private function getSends($token, $offset = 0)
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
            'offset' => $offset,
        ]);
    }
}
