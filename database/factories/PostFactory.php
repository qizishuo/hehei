<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Entities\Phone;
use App\Entities\ChildAccount;
use Faker\Generator as Faker;

$factory->define(Phone::class, function (Faker $faker) {
    return [
        'phone' => $faker->phoneNumber,
        'child_account_id' => ChildAccount::inRandomOrder()->first(),
    ];
});
