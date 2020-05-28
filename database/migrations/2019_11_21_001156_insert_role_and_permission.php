<?php

use App\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertRoleAndPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $admin_role = Role::create(["name" => "admin"]);
        $kefu_role = Role::create(["name" => "kefu"]);
        $apply_permission = Permission::create(["name" => "apply"]);
        $kefu_role->givePermissionTo($apply_permission);
        $admin_user = User::where("name", "admin")->first();
        if (empty($admin_user)) {
            $admin_user = User::create([
                "name" => "admin",
                "email" => "admin@dayouqifu.com",
                "password" => Hash::make("admin"),
            ]);
        }
        $admin_user->assignRole("admin");
        $kefu_user = User::create([
            "name" => "kefu",
            "email" => "kefu@dayouqifu.com",
            "password" => Hash::make("6v4Qw5ilZvIfrrY1"),
        ]);
        $kefu_user->assignRole("kefu");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $kefu_user = User::where("name", "kefu")->first();
        $kefu_user->removeRole("kefu");
        $kefu_user->delete();
        $admin_user = User::where("name", "admin")->first();
        $admin_user->removeRole("admin");
        $kefu_role = Role::findByName("kefu");
        $kefu_role->revokePermissionTo("apply");
        $kefu_role->delete();
        $admin_role = Role::findByName("admin");
        $admin_role->delete();
        Permission::where("name", "apply")->delete();
    }
}
