<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Migration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate Old from CSV';

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
        $file = fopen('/var/www/bitcorns.com/storage/app/migration.csv', "r");
        while (($data = fgetcsv($file)) !== FALSE) {
            $p = \App\Player::whereAddress($data[0])->first();
            $p->update([
                'name' => $data[1],
                'description' => $data[2],
                'image_url' => asset($data[3]),
            ]);
        }
    }
}