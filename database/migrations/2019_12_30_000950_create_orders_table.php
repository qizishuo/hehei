<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('CREATE DATABASE IF NOT EXISTS ' . env("DB_QIMING", "online_qiming"));

        Schema::connection("qiming")->create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code');
            $table->string('surname');
            $table->tinyInteger('gender');
            $table->timestamp('birthday');
            $table->tinyInteger('type');
            $table->tinyInteger('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection("qiming")->dropIfExists('orders');
    }
}
