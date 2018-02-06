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

$factory->define(App\Player::class, function (Faker $faker) {
    return [
        'address' => '1' . str_replace(':', '', $faker->ipv6),
        'name' => ucwords($faker->word()) . ' Farm',
        'description' => $faker->sentence(),
        'image_url' => asset('img/farms/' . rand(1, 12) . '.jpg'),
    ];
});
