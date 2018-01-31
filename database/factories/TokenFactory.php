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

$factory->define(App\Token::class, function (Faker $faker) {
    return [
        'type' => $faker->randomElement(['access', 'reward', 'upgrade']),
        'name' => strtoupper($faker->word),
    ];
});
