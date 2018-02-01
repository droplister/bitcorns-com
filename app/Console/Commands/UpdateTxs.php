<?php

namespace App\Console\Commands;

use JsonRPC\Client;
use Illuminate\Console\Command;

class UpdateTxs extends Command
{
    protected $counterparty;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:txs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Find and Create Transactions';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->counterparty = new Client(env('CP_API'));
        $this->counterparty->authentication(env('CP_USER'), env('CP_PASS'));

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $types = ['issuance', 'send', 'order', 'dividend'];

        if($block_index = $this->getBlockIndex())
        {
            foreach(\App\Token::orderBy('type', 'asc')->get() as $token)
            {
                foreach($types as $type)
                {
                    \App\Jobs\UpdateTxType::dispatch($token, $type);
                }

                if('access' == $token->type)
                {
                    \App\Jobs\UpdatePlayers::dispatch($token);
                }

                if('reward' == $token->type)
                {
                    \App\Jobs\UpdateRewards::dispatch($token);
                }

                \App\Jobs\UpdateBalances::dispatch($token);
            }
        }
    }

    private function getBlockIndex()
    {
        $info = $this->counterparty->execute('get_running_info');

        if($info['bitcoin_block_count'] !== \Cache::get('block_index'))
        {
            $block_index = \Cache::get('block_index');
            $this->setBlockIndex($info['bitcoin_block_count']);

            return $block_index ? $block_index : 1;
        }

        return false;
    }

    private function setBlockIndex($block_index)
    {
        \Cache::forget('block_index');
        \Cache::forever('block_index', $block_index);
    }
}
