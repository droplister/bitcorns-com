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
            [
                'type' => 'access',
                'name' => 'CROPS',
                'content' => 'Crops (CROPS:XCP) are arable block spaces suitable for growing Bitcorn on the blockchain. Addresses owning CROPS become bitcorn farms and can harvest Bitcorn, seasonally, until the Winter of 2022. There will only every be 100 CROPS.',
                'image_url' => asset('/img/tokens/CROPS.png'),
                'thumb_url' => asset('/img/tokens/thumbs/CROPS.png'),
            ],[
                'type' => 'reward',
                'name' => 'BITCORN',
                'content' => 'Bitcorn (BITCORN:XCP) is a kind of cryptographically modified organism (CMO) that is harvested four times per year by farms sowing Bitcorn Crops. Resistant to bugs and censorship of all types, there will only ever be 21,000,000 BITCORN.',
                'image_url' => asset('/img/tokens/BITCORN.png'),
                'thumb_url' => asset('/img/tokens/thumbs/BITCORN.png'),
            ],[
                'type' => 'trophy',
                'name' => 'BRAGGING',
                'content' => 'Bragging (BRAGGING:XCP) is a trophy token that will be awarded in the Year 2022 to the Bitcorns.com farm that harvests the most Bitcorn in total. Literal cryptographic bragging rights are on the line here, so bring your \'A\' game!',
                'image_url' => asset('/img/tokens/BRAGGING.png'),
                'thumb_url' => asset('/img/tokens/thumbs/BRAGGING.png'),
            ],[
                'type' => 'trophy',
                'name' => 'SQUADGOALS',
                'content' => 'Squad Goals (SQUADGOALS:XCP) is the trophy token that will be awarded in the Year 2022 to the Bitcorns.com coop that harvests the most Bitcorn in total. Ownership will go to the coop\'s creator and one token will go to each member farm.',
                'image_url' => asset('/img/tokens/SQUADGOALS.png'),
                'thumb_url' => asset('/img/tokens/thumbs/SQUADGOALS.png'),
            ],[
                'type' => 'upgrade',
                'name' => 'HELIPAD',
                'content' => 'Helipad (HELIPAD:XCP) is a farm upgrade token that will be available for purchase using BITCORN starting on April 1st, 2018. (Helicopter not included.) This token\'s art and price will be announced soon, so stay tuned!',
                'image_url' => null,
                'thumb_url' => null,
            ],[
                'type' => 'upgrade',
                'name' => 'YACHTDOCK',
                'content' => 'Yacht Dock (YACHTDOCK:XCP) is a farm upgrade token that will be available for purchase using BITCORN starting on April 1st, 2018. (Yacht not included.) This token\'s art and price will be announced soon, so stay tuned!',
                'image_url' => null,
                'thumb_url' => null,
            ],[
                'type' => 'upgrade',
                'name' => 'LAMBOGARAGE',
                'content' => 'Lambo Garage (LAMBOGARAGE:XCP) is a farm upgrade token that will be available for purchase using BITCORN starting on April 1st, 2018. (Lambo not included.) This token\'s art and price will be announced soon, so stay tuned!',
                'image_url' => null,
                'thumb_url' => null,
            ],
        ];

        foreach($tokens as $token_data)
        {
            $token = \App\Token::create([
                'name' => $token_data['name'],
                'type' => $token_data['type'],
                'content' => $token_data['content'],
                'image_url' => $token_data['image_url'],
                'thumb_url' => $token_data['thumb_url'],
            ]);

            \App\Jobs\UpdateToken::dispatch($token);
        }
    }
}
