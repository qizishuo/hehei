<?php

namespace App\Http\Controllers\Crm\Admin;

use App\Services\TokenService;
use App\User;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    public function login(Request $request)
    {

        $auth = $request->header("Authorization");
        if(empty($auth)){
            abort(400, "缺失必要参数：Authorization");
        }
        [$username, $password] = $this->parseAuth($auth);

        $user = User::where("name", $username)->first();
        if (empty($user) || !$user->verifyPassword($password)) {
            abort(401, "登陆失败，用户名密码错误");
        }


        $token_service = new TokenService("admin");
        $token = $token_service->write($user->id);

        return $this->jsonSuccessData(['token' => $token]);
    }

    public function logout(Request $request)
    {
        $auth = $request->header("Authorization");
        $token = $this->parseAuth($auth, "Bearer");

        $token_service = new TokenService("admin");
        $token_service->delete($token);

        return response("", 204);
    }


}
