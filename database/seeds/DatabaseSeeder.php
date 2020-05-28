<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call(ChildAccountSeeder::class);
         $this->call(CheckNameSeeder::class);
         $this->call(PopupSeeder::class);
         $this->call(InfoSeeder::class);
         $this->call(MoneySeeder::class);
         $this->call(ApplySeeder::class);
    }
}
