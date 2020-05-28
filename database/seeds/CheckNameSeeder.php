<?php

use App\Entities\CheckName;
use Illuminate\Database\Seeder;

class CheckNameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(CheckName::class, 50)->create();
    }
}
