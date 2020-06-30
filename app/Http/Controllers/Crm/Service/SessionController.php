<?php

namespace App\Http\Controllers\Crm\Admin;

use App\User;
use Exception;
use Illuminate\Http\Request;
use App\Services\TokenService;
use App\Http\Controllers\Controller;

class SessionController extends Controller
{
    public function login(Request $request)
    {
        $auth = $request->header("Authorization");
        [$username, $password] = $this->parseAuth($auth);

        $user = User::where("name", $username)->first();
        if (!$user->verifyPassword($password)) {
            abort(401, "登陆失败，用户名密码错误");
        }

        $token_service = new TokenService("admin");
        $token = $token_service->write($user->id);

        return $token;
    }

    public function logout(Request $request)
    {
        $auth = $request->header("Authorization");
        $token = $this->parseAuth($auth, "Bearer");

        $token_service = new TokenService("admin");
        $token_service->delete($token);

        return response("", 204);
    }

    private function parseAuth(string $auth, string $restrict_type = "Basic")
    {
        [$type, $data] = explode(" ", $auth);

        if ($type != $restrict_type) {
            abort(400, "Authorization 类型错误");
        }

        switch ($type) {
            case "Basic":
                $data = base64_decode($data);
                return explode(":", $data);
            case "Bearer":
                return $data;
            default:
                abort(400, "Authorization 解析失败");
        }
    }
}
