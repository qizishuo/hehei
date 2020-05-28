<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("phone");
            $table->bigInteger('child_account_id');
            $table->timestamps();
        });
        Schema::table("infos", function (Blueprint $table) {
            $table->renameColumn('phone', 'old_phone');
        });
        Schema::table("infos", function (Blueprint $table) {
            $table->bigInteger("phone_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('phones');
        Schema::table("infos", function (Blueprint $table) {
            $table->renameColumn('old_phone', 'phone');
        });
        Schema::table("infos", function (Blueprint $table) {
            $table->dropColumn(["phone_id"]);
        });
    }
}
