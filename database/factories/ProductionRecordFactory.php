<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\ProductionRecord;
use App\Model\ProductionLine;
use Faker\Generator as Faker;

$factory->define(ProductionRecord::class, function (Faker $faker) {
    $goodParts = $faker->numberBetween(800, 1200);
    $defectiveParts = $faker->numberBetween(10, 100);

    return [
        'production_line_id' => factory(ProductionLine::class),
        'production_date' => $faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
        'good_parts' => $goodParts,
        'defective_parts' => $defectiveParts,
        'efficiency' => round(($goodParts / ($goodParts + $defectiveParts)) * 100, 2),
    ];
});
