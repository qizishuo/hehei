<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection("qiming")->create('names', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("xing");
            $table->string("ming");
            $table->tinyInteger("sex");
            $table->float("score");
            $table->tinyInteger("active");
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
        Schema::connection("qiming")->dropIfExists('names');
    }
}
