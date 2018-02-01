<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Tx::class, function (Faker $faker) {
    return [
        'token_id' => factory(App\Token::class)->create()->id,
        'type' => $faker->randomElement(['issuance', 'order', 'send', 'dividend']),
        'block_index' => $faker->numberBetween(300000, 500000),
        'tx_index' => $faker->numberBetween(1, 1000000),
        'tx_hash' => hash('sha256', $faker->word),
    ];
});
