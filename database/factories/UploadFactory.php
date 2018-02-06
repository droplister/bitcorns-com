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

$factory->define(App\Upload::class, function (Faker $faker) {
    return [
        'player_id' => factory(App\Player::class)->create()->id,
        'new_image_url' => asset('img/farms/' . rand(1, 12) . '.jpg'),
        'old_image_url' => asset('img/farms/' . rand(1, 12) . '.jpg'),
    ];
});
