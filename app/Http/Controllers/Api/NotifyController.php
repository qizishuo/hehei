<?php

namespace App\Http\Controllers\Api;

use App\Services\Redis;
use Illuminate\Http\Request;

class NotifyController extends Controller
{
    public function captcha(Request $request)
    {
        $phone = $request->json("phone");
        $ip = $request->ip();
        $captcha_redis = new Redis("captcha", $phone);
        $ip_redis = new Redis("ip", $ip);

        if ($captcha_redis->ttl() > 60 * 4 || $ip_redis->exists()) {
            return response("请稍后尝试～", 429);
        }

        $captcha = str_pad(mt_rand(0, 999999), 6, "0", STR_PAD_BOTH);
        $captcha_redis->setex(60 * 5, $captcha);
        $ip_redis->setex(60, 1);

        if (send_captcha($phone, $captcha)) {
            return response("", 204);
        }

        return response("发送失败，请稍后再试！", 500);
    }
}
