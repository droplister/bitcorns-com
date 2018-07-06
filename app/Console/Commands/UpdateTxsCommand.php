<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateTxsCommand extends Command
{
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

        foreach(\App\Token::orderBy('type', 'asc')->get() as $token)
        {
            foreach($types as $type)
            {
                \App\Jobs\UpdateTxType::dispatch($token, $type);
            }
        }
    }
}
