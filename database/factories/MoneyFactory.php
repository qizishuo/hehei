<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Entities\ChildAccount;
use App\Entities\Money;
use Faker\Generator as Faker;

$factory->define(Money::class, function (Faker $faker) {
    $type = random_int(1, 3);
    $amount = $faker->randomFloat();
    return [
        'child_account_id' => ChildAccount::inRandomOrder()->first(),
        "amount" => $type == Money::TYPE_CONSUME ? -$amount : $amount,
        'type' => $type,
        'comment' => $faker->sentence,
    ];
});
