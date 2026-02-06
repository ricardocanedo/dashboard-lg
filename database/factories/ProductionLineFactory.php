<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\ProductionLine;
use App\Model\Plant;
use Faker\Generator as Faker;

$factory->define(ProductionLine::class, function (Faker $faker) {
    return [
        'name' => $faker->randomElement(['Geladeira', 'Máquina de Lavar', 'TV', 'Ar-Condicionado']),
        'plant_id' => factory(Plant::class),
    ];
});
