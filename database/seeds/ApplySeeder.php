<?php

use App\Entities\Apply;
use Illuminate\Database\Seeder;

class ApplySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Apply::class, 50)->create();
    }
}
