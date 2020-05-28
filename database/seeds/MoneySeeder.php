<?php

use App\Entities\Money;
use Illuminate\Database\Seeder;

class MoneySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Money::class, 50)->create();
    }
}
