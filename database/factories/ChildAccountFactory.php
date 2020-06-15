<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Entities\ChildAccount;
use Faker\Generator as Faker;

$factory->define(ChildAccount::class, function (Faker $faker) {
    $data = \cn\GB2260::getData();

    return [
        'name' => $faker->name,
        'type' => random_int(1, 3),
        'location' => array_rand($data),
        'amount' => '0.00',
        'status' => random_int(1, 3),
    ];
});
