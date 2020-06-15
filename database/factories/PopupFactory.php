<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Entities\Popup;
use Faker\Generator as Faker;

$factory->define(Popup::class, function (Faker $faker) {
    return [
        'phone' => $faker->phoneNumber,
        'company_name' => $faker->company,
        'name' => $faker->name,
    ];
});
