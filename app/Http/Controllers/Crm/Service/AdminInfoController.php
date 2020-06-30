<?php


namespace App\Http\Controllers\Crm\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
class AdminInfoController extends Controller
{
    public function ChangePassword(Request $request){
        $user = $request->get('user');
        $data = $request->validate([
            "old_password" => "required",
            "new_password" => ["required","different:old_password"],
            "confirm_password" => ["required","same:new_password"],
        ],[
            "old_password.required" => "旧密码不能为空",
            "new_password.required" => "新密码不能为空",
            "new_password.different" => "新旧密码不能相同",
            "confirm_password.required" => "确认密码不能为空",
            "confirm_password.same"     => "密码与确认密码不一致",
        ]);

        if(!$user->verifyPassword($data["old_password"])) {
            return $this->jsonErrorData(0,'旧密码输入错误');
        }
        $user->password = Hash::make($data["new_password"]);
        $user->save();
        return $this->jsonSuccessData();
    }

    public function info(Request $request){
        $user = $request->get('user');
        if($request->isMethod('get')){
            return $this->jsonSuccessData(
                [
                    'data' => [
                        'name'   => $user->name,
                        'avatar' => $user->avatar,
                        'tel'    => $user->tel,
                        'sex'    => $user->sex
                    ]
                ]
            );
        }
        $request->validate([
            "name" => "required",
            "tel" => ["required","regex:/^1[345789][0-9]{9}$/"],
        ],[
            "name.required" => "请填写管理员姓名",
            "tel.required" => "请填写管理员手机号",
            "tel.regex" => "手机号填写格式不对",
        ]);
        if($request->file('avatar')){
            $user->avatar = $request->file('avatar')->store('/avatars');
        }

        if($request->post('name')){
            $user->name = $request->post('name');
        }
        if($request->post('tel')){
            $user->tel = $request->post('tel');
        }
        if($request->post('sex')){
            $user->sex = $request->post('sex');
        }

        $user->save();
        return  $this->jsonSuccessData();
    }

}
