<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChildAccountsWxappOpenid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('child_accounts', function (Blueprint $table) {
            $table->string('wxapp_openid')->before('openid')->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('child_accounts', function (Blueprint $table) {
            $table->dropColumn('wxapp_openid');
        });
    }
}
