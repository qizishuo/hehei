<?php

use Illuminate\Database\Seeder;
use App\Entities\ChildAccount;

class ChildAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(ChildAccount::class, 50)->create();
    }
}
