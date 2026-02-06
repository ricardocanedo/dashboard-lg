<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Plant;
use Faker\Generator as Faker;

$factory->define(Plant::class, function (Faker $faker) {
    return [
        'name' => 'Plant ' . $faker->randomLetter(),
    ];
});
