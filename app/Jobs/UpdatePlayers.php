<?php

namespace App\Jobs;

use JsonRPC\Client;
use Faker\Factory as Faker;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdatePlayers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $counterparty;
    protected $faker;
    protected $token;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(\App\Token $token)
    {
        $this->counterparty = new Client(env('CP_API'));
        $this->counterparty->authentication(env('CP_USER'), env('CP_PASS'));
        $this->faker = Faker::create();
        $this->token = $token;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $holders = $this->counterparty->execute('get_holders', [
            'asset' => $this->token->name,
        ]);

        foreach($holders as $holder)
        {
            $player = \App\Player::firstOrCreate([
                'address' => $holder['address']
            ],[
                'name' => ucwords($this->faker->word()) . ' Farm',
                'content' => $this->getCornyQuote(),
                'image_url' => asset('img/farms/' . rand(1, 12) . '.jpg'),
            ]);

            if($player->wasRecentlyCreated)
            {
                \App\Jobs\UpdatePlayerType::dispatch($player, $this->token);
            }
        }
    }

    private function getCornyQuote()
    {
        $quotes = [
            'Dwight D. Eisenhower'  => 'Farming looks mighty easy when your plow is a pencil and you\'re a thousand miles from the corn field.',
            'Torquato Tasso'        => 'The day of fortune is like a harvest day, We must be busy when the corn is ripe.',
            'Anne Bronte'           => 'A light wind swept over the corn, and all nature laughed in the sunshine.',
            'William Bernbach'      => 'Today\'s smartest advertising style is tomorrow\'s corn.',
            'Michael Pollan'        => 'Corn is a greedy crop, as farmers will tell you.',
            'Masanobu Fukuoka'      => 'The ultimate goal of farming is not the growing of crops, but the cultivation and perfection of human beings.',
            'Cato the Elder'        => 'It is thus with farming: if you do one thing late, you will be late in all your work.',
            'Arthur Keith'          => 'The discovery of agriculture was the first big step toward a civilized life.',
            'Samuel Johnson'        => 'Agriculture not only gives riches to a nation, but the only riches she can call her own.',
            'Sam Farr'              => 'To make agriculture sustainable, the grower has got to be able to make a profit.',
            'Xenophon'              => 'Agriculture for an honorable and highminded man, is the best of all occupations or arts by which men procure the means of living.',
            'Thomas Jefferson'      => 'Agriculture is our wisest pursuit, because it will in the end contribute most to real wealth, good morals, and happiness.',
            'Paul Chatfield'        => 'Agriculture is the noblest of all alchemy; for it turns earth, and even manure, into gold, conferring upon its cultivator the additional reward of health.',
            'Marcus Tullius Cicero' => 'For of all gainful professions, nothing is better, nothing more pleasing, nothing more delightful, nothing better becomes a well-bred man than agriculture.',
            'unknown'               => 'You can make a small fortune in farming-provided you start with a large one.',
            'George Washington'     => 'Agriculture is the most healthful, most useful, and most noble employment of man.',
            'Brian Brett'           => 'Farming is a profession of hope.',
            'Samuel Johnson'        => 'If we estimate dignity by immediate usefulness, agriculture is undoubtedly the first and noblest science.',
            'Douglas Jerrold'       => 'If you tickle the earth with a hoe she laughs with a harvest.',
        ];

        $author = array_rand($quotes);
        $quote = $quotes[$author];

        return '"' . $quote . '" &ndash; ' . $author;
    }
}
