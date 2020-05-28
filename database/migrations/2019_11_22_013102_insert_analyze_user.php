<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;

class InsertAnalyzeUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $analyze_role = Role::create(["name" => "analyze"]);
        $analyze_permission = Permission::create(["name" => "analyze"]);
        $analyze_role->givePermissionTo($analyze_permission);
        $analyze_user = User::create([
            "name" => "analyze",
            "email" => "analyze@dayouqifu.com",
            "password" => Hash::make("74ZaPsyZsxkfr5y6"),
        ]);
        $analyze_user->assignRole("analyze");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $analyze_user = User::where("name", "analyze")->first();
        $analyze_user->removeRole("analyze");
        $analyze_user->delete();
        $analyze_role = Role::findByName("analyze");
        $analyze_role->revokePermissionTo("analyze");
        $analyze_role->delete();
        Permission::where("name", "analyze")->delete();
    }
}
