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

$factory->define(App\Balance::class, function (Faker $faker) {
    return [
        'token_id' => factory(App\Token::class)->create()->id,
        'tx_id' => factory(App\Tx::class)->create()->id,
        'total' => $faker->numberBetween(30000000, 50000000),
        'per_token' => $faker->numberBetween(300000, 500000),
    ];
});
