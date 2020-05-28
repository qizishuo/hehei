<?php

use  Illuminate\Database\Seeder;
use App\Entities\Phone;

class PhoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Phone::class, 50)->create();

    }
}
