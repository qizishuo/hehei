<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Entities\ChildAccount;
use App\Entities\Phone;
use App\Entities\Info;
use Faker\Generator as Faker;

$factory->define(Info::class, function (Faker $faker) {
    $child_account = ChildAccount::inRandomOrder()->first();
    return [
        'type' => random_int(1, 3),
        'source' => $faker->word,
        'company_name' => $faker->company,
        'phone_id' => Phone::inRandomOrder()->first(),
        'child_account_id' => $child_account,
        'location' => $child_account,
    ];
});
