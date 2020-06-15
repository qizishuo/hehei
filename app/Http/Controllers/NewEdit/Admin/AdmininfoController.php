<?php

namespace App\Http\Controllers\NewEdit\Admin;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\NewEdit\Controller;
class AdmininfoController extends Controller
{
   
     public function ChangePassword(Request $request){
        $user = $request->get('user');
        $data = $request->validate([
            "new_password" => ["required"],
            "confirm_password" => ["required","same:new_password"],

        ],[
            "new_password.required" => "新密码不能为空",
            "confirm_password.required" => "确认密码不能为空",
            "confirm_password.same"     => "密码与确认密码不一致",

        ]);


        $user->password =  Hash::make($data["new_password"]);

        $user->save();

        return $this->jsonSuccessData();

    }

}
