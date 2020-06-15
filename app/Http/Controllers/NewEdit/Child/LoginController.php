<?php


namespace App\Http\Controllers\NewEdit\Child;

use App\Services\TokenService;
use App\Entities\ChildAccount;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\NewEdit\Controller;
class LoginController extends Controller
{
    public function login(Request $request)
    {
        $auth = $request->header("Authorization");
        if(empty($auth)){
            abort(401, "缺失必要参数：Authorization");
        }

        [$username, $password] = $this->parseAuth($auth);

        $user = ChildAccount::where("name", $username)->first();
        if (empty($user) || !$user->verifyPassword($password)) {
            abort(401, "登陆失败，用户名密码错误");
        }


        $token_service = new TokenService("child");
        $token = $token_service->write($user->id);

        return $this->jsonSuccessData(['token' => $token]);
    }

    public function logout(Request $request)
    {
        $auth = $request->header("Authorization");
        $token = $this->parseAuth($auth, "Bearer");

        $token_service = new TokenService("child");
        $token_service->delete($token);

        return $this->jsonSuccessData();
    }


}
