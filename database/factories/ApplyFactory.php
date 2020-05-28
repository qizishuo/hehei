<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Entities\Apply;
use App\Entities\ChildAccount;
use App\Entities\Info;
use Faker\Generator as Faker;

$factory->define(Apply::class, function (Faker $faker) {
    return [
        "info_id" => Info::inRandomOrder()->first(),
        "child_account_id" => ChildAccount::inRandomOrder()->first(),
        "apply_reason" => $faker->sentence,
        "status" => random_int(1, 3),
        "refuse_reason" => $faker->sentence,
    ];
});
