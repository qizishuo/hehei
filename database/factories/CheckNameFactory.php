<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Entities\CheckName;
use Faker\Generator as Faker;

$factory->define(CheckName::class, function (Faker $faker) {
    return [
        'phone' => $faker->phoneNumber,
        'name' => $faker->company,
        'status' => random_int(1, 3),
    ];
});
