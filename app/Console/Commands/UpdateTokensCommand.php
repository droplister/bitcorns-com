<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateTokensCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:tokens';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Game Token Data';

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
        foreach(\App\Token::get() as $token)
        {
            \App\Jobs\UpdateToken::dispatch($token);
        }
    }
}
