<?php

use Illuminate\Database\Seeder;

class TokensTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tokens = [
            'CROPS' => 'access',
            'BITCORN' => 'reward',
            'HELIPAD' => 'upgrade',
            'YACHTDOCK' => 'upgrade',
            'LAMBOGARAGE' => 'upgrade',
        ];

        foreach($tokens as $name => $type)
        {
            \App\Token::create([
                'name' => $name,
                'type' => $type,
            ]);
        }

        \Artisan::call('update:tokens');
    }
}
