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

$factory->define(App\Achievement::class, function (Faker $faker) {
    return [
        'type' => $faker->randomElement(['global', 'player', 'balance', 'reward', 'tx']),
        'name' => ucwords($faker->words(3, true)),
        'description' => $faker->sentence(),
        'image_url' => asset('img/farms/' . rand(1, 12) . '.jpg'),
    ];
});
