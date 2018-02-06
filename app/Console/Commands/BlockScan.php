<?php

namespace App\Console\Commands;

use JsonRPC\Client;
use Illuminate\Console\Command;

class BlockScan extends Command
{
    protected $counterparty;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'block:scan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Monitor for New Blocks';

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
        if($this->isNewBlockHeight())
        {
            $this->call('update:txs');
            $this->comment('Updated: Tx');
            $this->call('update:players');
            $this->comment('Updated: Player');
            $this->call('update:balances');
            $this->comment('Updated: Balance');
            $this->call('update:rewards');
            $this->comment('Updated: Reward');
        }
    }

    private function isNewBlockHeight()
    {
        $block_height = $this->getBlockHeight();

        if($block_height !== \Cache::get('block_height'))
        {
            $this->setBlockHeight($block_height);

            return true;
        }

        return false;
    }

    private function getBlockHeight()
    {
        $info = $this->counterparty->execute('get_running_info');

        return $info['bitcoin_block_count'];
    }

    private function setBlockHeight($block_height)
    {
        \Cache::forget('block_height');
        \Cache::forever('block_height', $block_height);
    }
}